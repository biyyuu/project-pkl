<?php

namespace App\Http\Controllers;

use App\Models\Borrower;
use App\Models\ItemHistory;
use App\Models\ItemOutgoing;
use App\Models\SstockBrg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemOutgoingController extends Controller
{
    /**
     * Display a listing of the resource.
     * Only shows approved items (pending items are on the approval page).
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = ItemOutgoing::with(['item', 'borrower', 'recorder'])
            ->where('status', 'approved');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('item', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%")
                        ->orWhere('noinven', 'like', "%{$search}%");
                })
                ->orWhereHas('borrower', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%");
                })
                ->orWhere('keperluan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_keluar', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('tanggal_keluar', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('tanggal_keluar', '<=', $request->end_date);
        }

        $outgoings = $query->orderByDesc('tanggal_keluar')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $items = SstockBrg::query()
            ->where('stock', '>', 0)
            ->whereRaw("LOWER(TRIM(COALESCE(kondisi, ''))) = ?", ['baik'])
            ->orderBy('nama')
            ->get();

        $borrowers = Borrower::orderBy('nama')->get();

        return view('item-outgoing', compact('user', 'outgoings', 'items', 'borrowers'));
    }

    /**
     * Store a newly created resource in storage.
     * Stock is decremented immediately so the approval page reflects reserved items.
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:sstock_brg,idx',
            'borrower_id' => 'required|exists:borrowers,id',
            'jumlah_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_keluar',
            'keperluan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $stockItem = SstockBrg::findOrFail($request->item_id);

        if ($stockItem->stock < $request->jumlah_keluar) {
            return back()
                ->withInput()
                ->withErrors(['jumlah_keluar' => 'Stok tidak mencukupi. Stok tersedia: ' . $stockItem->stock]);
        }

        DB::transaction(function () use ($request, $stockItem) {
            ItemOutgoing::create([
                'item_id' => $stockItem->idx,
                'borrower_id' => $request->borrower_id,
                'recorded_by' => auth()->id(),
                'jumlah_keluar' => $request->jumlah_keluar,
                'tanggal_keluar' => $request->tanggal_keluar,
                'tanggal_kembali' => $request->tanggal_kembali,
                'keperluan' => $request->keperluan,
                'keterangan' => $request->keterangan,
                'status' => 'pending',
            ]);

            $stockItem->decrement('stock', $request->jumlah_keluar);
        });

        return redirect()->route('item-outgoing.index')
            ->with('success', 'Permintaan peminjaman barang berhasil dibuat. Menunggu persetujuan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemOutgoing $itemOutgoing)
    {
        $request->validate([
            'item_id' => 'required|exists:sstock_brg,idx',
            'borrower_id' => 'required|exists:borrowers,id',
            'jumlah_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_keluar',
            'keperluan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $oldStockItem = SstockBrg::findOrFail($itemOutgoing->item_id);
        $newStockItem = SstockBrg::findOrFail($request->item_id);
        $oldJumlah = $itemOutgoing->jumlah_keluar;
        $newJumlah = $request->jumlah_keluar;

        DB::transaction(function () use ($request, $itemOutgoing, $oldStockItem, $newStockItem, $oldJumlah, $newJumlah) {
            if ($oldStockItem->idx === $newStockItem->idx) {
                $diff = $newJumlah - $oldJumlah;
                if ($diff > 0 && $newStockItem->stock < $diff) {
                    throw new \Exception('Stok tidak mencukupi. Stok tersedia: ' . $newStockItem->stock);
                }

                if ($diff !== 0) {
                    if ($diff > 0) {
                        $newStockItem->decrement('stock', $diff);
                    } else {
                        $newStockItem->increment('stock', abs($diff));
                    }
                }
            } else {
                $oldStockItem->increment('stock', $oldJumlah);

                if ($newStockItem->stock < $newJumlah) {
                    throw new \Exception('Stok tidak mencukupi. Stok tersedia: ' . $newStockItem->stock);
                }

                $newStockItem->decrement('stock', $newJumlah);
            }

            $itemOutgoing->update([
                'item_id' => $newStockItem->idx,
                'borrower_id' => $request->borrower_id,
                'jumlah_keluar' => $newJumlah,
                'tanggal_keluar' => $request->tanggal_keluar,
                'tanggal_kembali' => $request->tanggal_kembali,
                'keperluan' => $request->keperluan,
                'keterangan' => $request->keterangan,
            ]);

            ItemHistory::create([
                'item_id' => $newStockItem->idx,
                'user_id' => auth()->id(),
                'action' => 'edit',
                'jumlah_sebelum' => $oldJumlah,
                'jumlah_sesudah' => $newJumlah,
                'deskripsi' => 'Edit data barang keluar',
            ]);
        });

        return redirect()->route('item-outgoing.index')
            ->with('success', 'Data barang keluar berhasil diperbarui.');
    }

    /**
     * Mark a borrowing as completed (returned).
     * Restores stock back to daftar barang.
     */
    public function selesai(ItemOutgoing $itemOutgoing)
    {
        if ($itemOutgoing->status !== 'approved') {
            return back()->with('error', 'Hanya peminjaman yang sudah disetujui yang bisa diselesaikan.');
        }

        $stockItem = SstockBrg::findOrFail($itemOutgoing->item_id);

        DB::transaction(function () use ($itemOutgoing, $stockItem) {
            $jumlahSebelum = $stockItem->stock;

            $itemOutgoing->update(['status' => 'completed']);

            $stockItem->increment('stock', $itemOutgoing->jumlah_keluar);

            ItemHistory::create([
                'item_id' => $itemOutgoing->item_id,
                'user_id' => auth()->id(),
                'action' => 'selesai',
                'jumlah_sebelum' => $jumlahSebelum,
                'jumlah_sesudah' => $jumlahSebelum + $itemOutgoing->jumlah_keluar,
                'deskripsi' => 'Peminjaman selesai: ' . $itemOutgoing->jumlah_keluar . ' unit dikembalikan ke stok',
            ]);
        });

        return redirect()->route('item-outgoing.index')
            ->with('success', 'Peminjaman selesai. Stok barang telah dikembalikan ke daftar barang.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemOutgoing $itemOutgoing)
    {
        DB::transaction(function () use ($itemOutgoing) {
            if (in_array($itemOutgoing->status, ['pending', 'approved'])) {
                $stockItem = SstockBrg::findOrFail($itemOutgoing->item_id);
                $jumlahSebelum = $stockItem->stock;
                $stockItem->increment('stock', $itemOutgoing->jumlah_keluar);

                ItemHistory::create([
                    'item_id' => $itemOutgoing->item_id,
                    'user_id' => auth()->id(),
                    'action' => 'hapus',
                    'jumlah_sebelum' => $jumlahSebelum,
                    'jumlah_sesudah' => $jumlahSebelum + $itemOutgoing->jumlah_keluar,
                    'deskripsi' => 'Data barang keluar dihapus: ' . $itemOutgoing->jumlah_keluar . ' unit dikembalikan ke stok',
                ]);
            }

            $itemOutgoing->delete();
        });

        return redirect()->route('item-outgoing.index')
            ->with('success', 'Data barang keluar berhasil dihapus.');
    }
}
