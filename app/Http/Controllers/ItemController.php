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
        $items = Item::all();
        return view('item-list', compact('items'));
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
        $validated = $request->validate([
            'no_inventaris' => 'required|string|unique:items,no_inventaris',
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'nama_pengadaan' => 'nullable|string|max:255',
            'tahun_pengadaan' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'kondisi_barang' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'keterangan' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['created_by'] = auth()->id();

        Item::create($validated);

        return redirect()->route('item')->with('success', 'Barang berhasil ditambahkan!');
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
}
