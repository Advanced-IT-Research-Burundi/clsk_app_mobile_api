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
    public function index()
    {
        $products = Product::with(['category.type', 'devise', 'user'])->get();
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
            'exchange_rate' => 'nullable|numeric',
            'photos' => 'nullable|array',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'devise_id' => 'required|exists:devises,id',
        ]);

        $product = Product::create([
            ...$request->all(),
            'user_id' => $request->user()->id,
        ]);

        return new ProductResource($product->load(['category.type', 'devise']));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with(['category.type', 'devise', 'user'])->findOrFail($id);
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
            'exchange_rate' => 'nullable|numeric',
            'photos' => 'nullable|array',
            'date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'devise_id' => 'required|exists:devises,id',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        return new ProductResource($product->load(['category.type', 'devise']));
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
}
