<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category.type', 'devise', 'user', 'photos', 'supplier', 'container'])->latest();
        
        if ($request->boolean('archived')) {
            $query->archived();
        } else {
            $query->active();
        }

        $products = $query->paginate();
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'packaging' => 'nullable|string',
            'unit_per_package' => 'nullable|integer',
            'number_of_cartons' => 'nullable|integer',
            'exchange_rate' => 'nullable|numeric',
            'photo' => 'nullable|file|image',
            'customs_price' => 'nullable|numeric',
            'cbm' => 'nullable|numeric',
            'customs_price_currency' => 'nullable|in:USD,BIF,RMB',
            'total_bif' => 'nullable|numeric',
            'total_usd' => 'nullable|numeric',
            'total_rmb' => 'nullable|numeric',
            //'date' => 'required|date',
            'category_id' => 'nullable|exists:categories,id',
            'devise_id' => 'required|exists:devises,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $product = Product::create([
            ...$request->except('photo'),
            'user_id' => $request->user()->id,
            'date' => now(),
            // 'category_id' => 1,
            // 'devise_id' => 1,
        ]);

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            $imageName = time().'.'.$request->photo->extension();
            $path = $request->file('photo')->move('uploads/products', $imageName);
            $product->photos()->create(['url' => $path]);
        }

        return new ProductResource($product->load(['category.type', 'devise', 'photos']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category.type', 'devise', 'user', 'photos', 'supplier', 'container'])->findOrFail($id);
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'packaging' => 'nullable|string',
            'unit_per_package' => 'nullable|integer',
            'exchange_rate' => 'nullable|numeric',
            'customs_price' => 'nullable|numeric',
            'cbm' => 'nullable|numeric',
            'customs_price_currency' => 'nullable|in:USD,BIF,RMB',
            'total_bif' => 'nullable|numeric',
            'total_usd' => 'nullable|numeric',
            'total_rmb' => 'nullable|numeric',
            'photos' => 'nullable|array',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'devise_id' => 'required|exists:devises,id',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->except('photos'));

        if ($request->has('photos')) {
            $product->photos()->delete(); // Remove old photos
            foreach ($request->photos as $url) {
                $product->photos()->create(['url' => $url]);
            }
        }
        
        return new ProductResource($product->load(['category.type', 'devise', 'photos']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Archive the specified resource.
     */
    public function archive(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $container = \App\Models\Container::firstOrCreate(
            ['serial_number' => $request->serial_number],
            ['name' => $request->name, 'description' => $request->description]
        );

        $product->update([
            'is_archived' => true,
            'container_id' => $container->id
        ]);

        return response()->json(['message' => 'Product archived successfully', 'container' => $container]);
    }

    /**
     * Unarchive the specified resource.
     */
    public function unarchive(string $id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'is_archived' => false,
            'container_id' => null
        ]);

        return response()->json(['message' => 'Product unarchived successfully']);
    }

    /**
     * Bulk archive resources.
     */
    public function bulkArchive(Request $request)
    {
        $request->validate([
            'ids' => 'required|array', 
            'ids.*' => 'exists:products,id',
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $container = \App\Models\Container::firstOrCreate(
            ['serial_number' => $request->serial_number],
            ['name' => $request->name, 'description' => $request->description]
        );

        Product::whereIn('id', $request->ids)->update([
            'is_archived' => true,
            'container_id' => $container->id
        ]);

        return response()->json(['message' => 'Products archived successfully']);
    }

    /**
     * Bulk unarchive resources.
     */
    public function bulkUnarchive(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:products,id']);
        Product::whereIn('id', $request->ids)->update([
            'is_archived' => false,
            'container_id' => null
        ]);

        return response()->json(['message' => 'Products unarchived successfully']);
    }

    /**
     * Bulk delete resources.
     */
    public function bulkDelete(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'exists:products,id']);
        Product::whereIn('id', $request->ids)->delete();

        return response()->json(['message' => 'Products deleted successfully']);
    }

    /**
     * Generate a report of products (JSON)
     */
    public function report(Request $request)
    {
        $query = Product::with(['category', 'devise', 'user', 'photos', 'supplier'])->latest();

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->input('end_date'));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->input('supplier_id'));
        }
        if ($request->filled('container_id')) {
            $query->where('container_id', $request->input('container_id'));
        }
        if ($request->filled('devise_id')) {
            $query->where('devise_id', $request->input('devise_id'));
        }

        $products = $query->get();

        $data = $products->map(function (Product $p) {
            return [
                'id' => (string) $p->id,
                'name' => $p->name,
                'description' => $p->description,
                'price' => $p->price + 0,
                'currency' => $p->devise ? $p->devise->code : null,
                'quantity' => $p->quantity,
                'exchangeRate' => $p->exchange_rate + 0,
                'convertedPrice' => ($p->price * ($p->exchange_rate ?? 1)) + 0,
                'type' => $p->category && $p->category->type ? $p->category->type->name : null,
                'category' => $p->category ? $p->category->name : null,
                'packaging' => $p->packaging,
                'unit_per_package' => $p->unit_per_package,
                'number_of_cartons' => $p->number_of_cartons,
                'photo' => $p->photos->map(fn($ph) => url($ph->url))->toArray(),
                'supplier_id' => $p->supplier_id,
                'supplier_name' => $p->supplier ? $p->supplier->name : null,
                'date' => $p->date ? $p->date->format('Y-m-d') : null,
                'customs_price' => $p->customs_price + 0,
                'customs_price_currency' => $p->customs_price_currency,
                'cbm' => $p->cbm + 0,
                'total_bif' => $p->total_bif + 0,
                'total_usd' => $p->total_usd + 0,
                'total_rmb' => $p->total_rmb + 0,
            ];
        });

        return response()->json($data->values());
    }

    /**
     * Export the products report as PDF
     */
    public function exportPdfReport(Request $request)
    {
        $query = Product::with(['category.type', 'devise', 'user', 'photos', 'supplier', 'container'])->latest();

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->input('end_date'));
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->input('supplier_id'));
        }
        if ($request->filled('container_id')) {
            $query->where('container_id', $request->input('container_id'));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $products = $query->get();
        
        // Use Barryvdh\DomPDF\Facade\Pdf for generation
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.products', compact('products'));
        
        return $pdf->download('rapport-produits.pdf');
    }

    /**
     * Export the products report as CSV
     */
    public function exportReport(Request $request)
    {
        $query = Product::with(['category.type', 'devise', 'user', 'photos'])->latest();

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->input('start_date'));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->input('end_date'));
        }

        $products = $query->get();

        $callback = function () use ($products) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'id', 'name', 'description', 'price', 'currency', 'quantity', 'exchangeRate', 'convertedPrice', 'type', 'category', 'packaging', 'unit_per_package', 'number_of_cartons', 'photo', 'date'
            ]);

            foreach ($products as $p) {
                $photos = $p->photos->map(fn($ph) => url($ph->url))->toArray();
                $row = [
                    $p->id,
                    $p->name,
                    $p->description,
                    $p->price + 0,
                    $p->devise ? $p->devise->code : '',
                    $p->quantity,
                    $p->exchange_rate + 0,
                    ($p->price * ($p->exchange_rate ?? 1)) + 0,
                    $p->category && $p->category->type ? $p->category->type->name : '',
                    $p->category ? $p->category->name : '',
                    $p->packaging,
                    $p->unit_per_package,
                    $p->number_of_cartons,
                    implode('|', $photos),
                    $p->date ? $p->date->format('Y-m-d') : '',
                ];

                fputcsv($out, $row);
            }

            fclose($out);
        };

        return response()->streamDownload($callback, 'products-report.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }
}
