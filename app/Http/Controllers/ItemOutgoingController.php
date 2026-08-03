<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\ItemOutgoing;
use App\Models\Borrower;
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

        $query = ItemOutgoing::with(['item' => function ($q) { $q->withTrashed(); }, 'borrower', 'recorder'])
            ->where('status', 'approved');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('item', function ($q2) use ($search) {
                    $q2->where('nama_barang', 'like', "%{$search}%")
                       ->orWhere('no_inventaris', 'like', "%{$search}%");
                })
                ->orWhereHas('borrower', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%");
                })
                ->orWhere('keperluan', 'like', "%{$search}%");
            });
        }

        // Date range filter
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

        // Get available items for the modal (items with stock > 0)
        $items = Item::where('jumlah', '>', 0)
            ->where('kondisi_barang', 'baik')
            ->orderBy('nama_barang')
            ->get();

        // Get all borrowers for the modal
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
            'item_id' => 'required|exists:items,id',
            'borrower_id' => 'required|exists:borrowers,id',
            'jumlah_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_keluar',
            'keperluan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $item = Item::findOrFail($request->item_id);

        // Check if enough stock
        if ($item->jumlah < $request->jumlah_keluar) {
            return back()
                ->withInput()
                ->withErrors(['jumlah_keluar' => 'Stok tidak mencukupi. Stok tersedia: ' . $item->jumlah]);
        }

        DB::transaction(function () use ($request, $item) {
            // Create outgoing record with status pending
            ItemOutgoing::create([
                'item_id' => $request->item_id,
                'borrower_id' => $request->borrower_id,
                'recorded_by' => auth()->id(),
                'jumlah_keluar' => $request->jumlah_keluar,
                'tanggal_keluar' => $request->tanggal_keluar,
                'tanggal_kembali' => $request->tanggal_kembali,
                'keperluan' => $request->keperluan,
                'keterangan' => $request->keterangan,
                'status' => 'pending',
            ]);

            // Decrement stock immediately (reserved for this borrowing request)
            $item->decrement('jumlah', $request->jumlah_keluar);
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
            'item_id' => 'required|exists:items,id',
            'borrower_id' => 'required|exists:borrowers,id',
            'jumlah_keluar' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_keluar',
            'keperluan' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $oldItem = Item::findOrFail($itemOutgoing->item_id);
        $newItem = Item::findOrFail($request->item_id);
        $oldJumlah = $itemOutgoing->jumlah_keluar;
        $newJumlah = $request->jumlah_keluar;

        DB::transaction(function () use ($request, $itemOutgoing, $oldItem, $newItem, $oldJumlah, $newJumlah) {
            // Stock is already decremented (at borrow time), so adjust differences
            if ($oldItem->id === $newItem->id) {
                $diff = $newJumlah - $oldJumlah;
                if ($diff > 0 && $newItem->jumlah < $diff) {
                    throw new \Exception('Stok tidak mencukupi. Stok tersedia: ' . $newItem->jumlah);
                }
                if ($diff !== 0) {
                    if ($diff > 0) {
                        $newItem->decrement('jumlah', $diff);
                    } else {
                        $newItem->increment('jumlah', abs($diff));
                    }
                }
            } else {
                // Restore old item stock
                $oldItem->increment('jumlah', $oldJumlah);
                // Deduct from new item stock
                if ($newItem->jumlah < $newJumlah) {
                    throw new \Exception('Stok tidak mencukupi. Stok tersedia: ' . $newItem->jumlah);
                }
                $newItem->decrement('jumlah', $newJumlah);
            }

            $itemOutgoing->update([
                'item_id' => $request->item_id,
                'borrower_id' => $request->borrower_id,
                'jumlah_keluar' => $newJumlah,
                'tanggal_keluar' => $request->tanggal_keluar,
                'tanggal_kembali' => $request->tanggal_kembali,
                'keperluan' => $request->keperluan,
                'keterangan' => $request->keterangan,
            ]);

            // Log history
            ItemHistory::create([
                'item_id' => $request->item_id,
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

        $item = $itemOutgoing->item;

        DB::transaction(function () use ($itemOutgoing, $item) {
            $jumlahSebelum = $item->jumlah;

            $itemOutgoing->update(['status' => 'completed']);

            // Restore stock back to daftar barang
            $item->increment('jumlah', $itemOutgoing->jumlah_keluar);

            // Log history
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
            // Restore stock if the item was still borrowing (pending or approved)
            if (in_array($itemOutgoing->status, ['pending', 'approved'])) {
                $item = $itemOutgoing->item;
                $jumlahSebelum = $item->jumlah;
                $item->increment('jumlah', $itemOutgoing->jumlah_keluar);

                // Log history
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
