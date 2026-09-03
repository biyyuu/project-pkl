<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\ItemOutgoing;
use App\Models\SstockBrg;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $items = SstockBrg::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('noinven', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('merk', 'like', "%{$search}%")
                        ->orWhere('snumber', 'like', "%{$search}%")
                        ->orWhere('pengadaan', 'like', "%{$search}%")
                        ->orWhere('lokasi', 'like', "%{$search}%")
                        ->orWhere('kondisi', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%");
                });
            })
            ->orderBy('nama')
            ->paginate(25)
            ->withQueryString();

        return view('item-list', compact('items', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_inventaris' => 'required|string|unique:sstock_brg,noinven',
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'nama_pengadaan' => 'nullable|string|max:255',
            'tahun_pengadaan' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'kondisi_barang' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'keterangan' => 'nullable|string',
        ]);

        $item = SstockBrg::create([
            'noinven' => $validated['no_inventaris'],
            'nama' => $validated['nama_barang'],
            'merk' => $validated['merk'] ?? null,
            'snumber' => $validated['serial_number'] ?? null,
            'stock' => $validated['jumlah'],
            'pengadaan' => $validated['nama_pengadaan'] ?? null,
            'lokasi' => $validated['tahun_pengadaan'] ?? null,
            'kondisi' => $validated['kondisi_barang'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        ItemHistory::create([
            'item_id' => $item->idx,
            'user_id' => auth()->id(),
            'action' => 'tambah',
            'jumlah_sebelum' => 0,
            'jumlah_sesudah' => $item->stock,
            'deskripsi' => 'Menambahkan barang baru ke sistem.',
        ]);

        return redirect()->route('item')->with('success', 'Barang berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SstockBrg $item)
    {
        $validated = $request->validate([
            'no_inventaris' => 'required|string|unique:sstock_brg,noinven,' . $item->idx . ',idx',
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:0',
            'nama_pengadaan' => 'nullable|string|max:255',
            'tahun_pengadaan' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'kondisi_barang' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'keterangan' => 'nullable|string',
        ]);

        $jumlahSebelum = $item->stock;
        $item->update([
            'noinven' => $validated['no_inventaris'],
            'nama' => $validated['nama_barang'],
            'merk' => $validated['merk'] ?? null,
            'snumber' => $validated['serial_number'] ?? null,
            'stock' => $validated['jumlah'],
            'pengadaan' => $validated['nama_pengadaan'] ?? null,
            'lokasi' => $validated['tahun_pengadaan'] ?? null,
            'kondisi' => $validated['kondisi_barang'],
            'keterangan' => $validated['keterangan'] ?? null,
        ]);

        ItemHistory::create([
            'item_id' => $item->idx,
            'user_id' => auth()->id(),
            'action' => 'edit',
            'jumlah_sebelum' => $jumlahSebelum,
            'jumlah_sesudah' => $item->stock,
            'deskripsi' => 'Mengubah data barang / stok.',
        ]);

        return redirect()->route('item')->with('success', 'Data barang berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SstockBrg $item)
    {
        // Check if item has active outgoings
        if (ItemOutgoing::where('item_id', $item->idx)->exists()) {
            return redirect()->route('item')
                ->with('error', 'Barang tidak bisa dihapus karena masih memiliki data barang keluar terkait.');
        }

        $jumlahSebelum = $item->stock;
        $item->delete();

        ItemHistory::create([
            'item_id' => $item->idx,
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
            'no_inventaris' => 'required|string|unique:sstock_brg,noinven',
            'nama_barang' => 'required|string|max:255',
            'merk' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'kondisi_barang' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
        ]);

        $item = SstockBrg::create([
            'noinven' => $request->no_inventaris,
            'nama' => $request->nama_barang,
            'merk' => $request->merk,
            'snumber' => $request->serial_number,
            'stock' => $request->jumlah,
            'kondisi' => $request->kondisi_barang,
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'idx' => $item->idx,
                'noinven' => $item->noinven,
                'nama' => $item->nama,
                'merk' => $item->merk,
                'snumber' => $item->snumber,
                'stock' => $item->stock,
                'kondisi' => $item->kondisi,
            ]
        ]);
    }
}
