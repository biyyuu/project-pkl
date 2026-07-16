<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemHistory;
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

        $item = Item::create($validated);

        ItemHistory::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'action' => 'tambah',
            'jumlah_sebelum' => 0,
            'jumlah_sesudah' => $item->jumlah,
            'deskripsi' => 'Menambahkan barang baru ke sistem.',
        ]);

        return redirect()->route('item')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'no_inventaris' => 'required|string|unique:items,no_inventaris,' . $item->id,
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'nama_pengadaan' => 'nullable|string|max:255',
            'tahun_pengadaan' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'kondisi_barang' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'keterangan' => 'nullable|string',
        ]);

        $jumlahSebelum = $item->jumlah;
        $item->update($validated);

        ItemHistory::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'action' => 'edit',
            'jumlah_sebelum' => $jumlahSebelum,
            'jumlah_sesudah' => $item->jumlah,
            'deskripsi' => 'Mengubah data barang / stok.',
        ]);

        return redirect()->route('item')->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        // Check if item has active outgoings
        if ($item->outgoings()->exists()) {
            return redirect()->route('item')
                ->with('error', 'Barang tidak bisa dihapus karena masih memiliki data barang keluar terkait.');
        }

        $jumlahSebelum = $item->jumlah;
        $item->delete();

        ItemHistory::create([
            'item_id' => $item->id,
            'user_id' => auth()->id(),
            'action' => 'hapus',
            'jumlah_sebelum' => $jumlahSebelum,
            'jumlah_sesudah' => 0,
            'deskripsi' => 'Menghapus barang dari sistem.',
        ]);

        return redirect()->route('item')->with('success', 'Barang berhasil dihapus!');
    }

    /**
     * Store via AJAX (for quick-add in outgoing form)
     */
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
