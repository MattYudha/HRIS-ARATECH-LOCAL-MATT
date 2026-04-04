<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InventoryCategory;

class InventoryCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = InventoryCategory::all();
        return view('inventory-categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventory-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories',
            'description' => 'nullable|string',
        ]);

        InventoryCategory::create($request->all());

        return redirect()->route('inventory-categories.index')->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = InventoryCategory::findOrFail($id);
        return view('inventory-categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = InventoryCategory::findOrFail($id);
        return view('inventory-categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = InventoryCategory::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:inventory_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update($request->all());

        return redirect()->route('inventory-categories.index')->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = InventoryCategory::findOrFail($id);
        if ($category->inventories()->exists()) {
            return redirect()->route('inventory-categories.index')->with('error', 'Cannot delete category: It has associated inventory items.');
        }
        $category->delete();
        return redirect()->route('inventory-categories.index')->with('success', 'Category deleted successfully.');
    }
}
