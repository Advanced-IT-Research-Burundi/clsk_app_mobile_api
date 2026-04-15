<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DepenseProduit;
use App\Models\Product;
use Illuminate\Http\Request;

class DepenseProduitController extends Controller
{
    /**
     * List all depenses (optionally filtered by product).
     */
    public function index(Request $request)
    {
        $query = DepenseProduit::with('product:id,name')->latest();

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $depenses = $query->get()->map(fn ($d) => $this->format($d));

        return response()->json($depenses);
    }

    /**
     * List depenses for a specific product.
     */
    public function byProduct(string $productId)
    {
        $depenses = DepenseProduit::with('product:id,name')
            ->where('product_id', $productId)
            ->latest()
            ->get()
            ->map(fn ($d) => $this->format($d));

        return response()->json($depenses);
    }

    /**
     * Store a new depense.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id'  => 'required|exists:products,id',
            'montant'     => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'currency'    => 'required|string|max:10',
            'rate'        => 'required|numeric|min:0',
        ]);

        $depense = DepenseProduit::create($data);
        $depense->load('product:id,name');

        return response()->json($this->format($depense), 201);
    }

    /**
     * Update a depense.
     */
    public function update(Request $request, string $id)
    {
        $depense = DepenseProduit::findOrFail($id);

        $data = $request->validate([
            'montant'     => 'sometimes|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'currency'    => 'sometimes|string|max:10',
            'rate'        => 'sometimes|numeric|min:0',
        ]);

        $depense->update($data);
        $depense->load('product:id,name');

        return response()->json($this->format($depense));
    }

    /**
     * Delete a depense.
     */
    public function destroy(string $id)
    {
        DepenseProduit::findOrFail($id)->delete();
        return response()->json(['message' => 'Dépense supprimée.']);
    }

    private function format(DepenseProduit $d): array
    {
        return [
            'id'          => $d->id,
            'product_id'  => $d->product_id,
            'product_name'=> $d->product?->name,
            'montant'     => (float) $d->montant,
            'montant_bif' => $d->montant_bif,
            'description' => $d->description,
            'currency'    => $d->currency,
            'rate'        => (float) $d->rate,
            'created_at'  => $d->created_at?->format('Y-m-d H:i'),
        ];
    }
}
