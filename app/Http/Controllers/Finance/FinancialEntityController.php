<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\FinancialEntity;
use Illuminate\Http\Request;

class FinancialEntityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entities = FinancialEntity::when($request->search, function($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhere('contact_info', 'like', "%{$search}%");
        })->latest()->paginate(10);

        return view('finance.entities.index', compact('entities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('finance.entities.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:internal,bank,vendor,client,other',
            'name' => 'required|string|max:255',
            'contact_info' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $entity = FinancialEntity::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Entitas berhasil ditambahkan.',
                'data' => $entity
            ]);
        }

        return redirect()->route('finance.entities.index')->with('success', 'Entitas kas & keuangan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialEntity $entity)
    {
        return view('finance.entities.edit', compact('entity'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialEntity $entity)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:internal,bank,vendor,client,other',
            'name' => 'required|string|max:255',
            'contact_info' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $entity->update($validated);

        return redirect()->route('finance.entities.index')->with('success', 'Entitas kas & keuangan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialEntity $entity)
    {
        // Add check if there are associated transactions, prevent deletion if any (optional, but good practice).
        if ($entity->sentTransactions()->exists() || $entity->receivedTransactions()->exists()) {
            return redirect()->route('finance.entities.index')->with('error', 'Entitas tidak dapat dihapus karena memiliki transaksi yang terkait.');
        }

        $entity->delete();

        return redirect()->route('finance.entities.index')->with('success', 'Entitas kas & keuangan berhasil dihapus.');
    }
}
