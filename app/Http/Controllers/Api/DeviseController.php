<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Devise;
use Illuminate\Http\Request;

class DeviseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devises = Devise::all();
        return response()->json($devises);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:devises',
            'symbol' => 'nullable|string|max:10',
        ]);

        $devise = Devise::create($request->all());

        return response()->json($devise, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $devise = Devise::findOrFail($id);
        return response()->json($devise);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:devises,code,' . $id,
            'symbol' => 'nullable|string|max:10',
        ]);

        $devise = Devise::findOrFail($id);
        $devise->update($request->all());

        return response()->json($devise);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $devise = Devise::findOrFail($id);
        $devise->delete();

        return response()->json(['message' => 'Devise deleted successfully']);
    }
}
