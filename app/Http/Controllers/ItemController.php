<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }

    public function storeAjax(Request $request)
    {
        $request->validate([
            'no_inventaris' => 'required|string|unique:items,no_inventaris',
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'kondisi_barang' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
        ]);

        $data = $request->only([
            'no_inventaris', 'nama_barang', 'merk', 'serial_number', 'jumlah', 'kondisi_barang'
        ]);
        $data['user_id'] = auth()->id();
        $data['created_by'] = auth()->id();

        $item = Item::create($data);

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }
}
