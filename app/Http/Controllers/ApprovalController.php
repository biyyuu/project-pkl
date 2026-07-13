<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\ItemOutgoing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Ensure only Kasub or Admin can view
        if (!$user->hasRole(['admin', 'kasub'])) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $query = ItemOutgoing::with(['item', 'borrower', 'recorder'])
            ->where('status', 'pending');

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

        $outgoings = $query->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('approval', compact('user', 'outgoings'));
    }

    public function approve(Request $request, ItemOutgoing $itemOutgoing)
    {
        $user = auth()->user();
        if (!$user->hasRole(['admin', 'kasub'])) {
            return back()->with('error', 'Akses ditolak.');
        }

        if ($itemOutgoing->status !== 'pending') {
            return back()->with('error', 'Data sudah diproses sebelumnya.');
        }

        $item = $itemOutgoing->item;

        if ($item->jumlah < $itemOutgoing->jumlah_keluar) {
            return back()->with('error', 'Stok tidak mencukupi untuk disetujui. Stok tersedia: ' . $item->jumlah);
        }

        DB::transaction(function () use ($itemOutgoing, $item) {
            $jumlahSebelum = $item->jumlah;

            $itemOutgoing->update(['status' => 'approved']);
            $item->decrement('jumlah', $itemOutgoing->jumlah_keluar);

            // Log history AFTER approval
            ItemHistory::create([
                'item_id' => $itemOutgoing->item_id,
                'user_id' => $itemOutgoing->recorded_by,
                'action' => 'keluar',
                'jumlah_sebelum' => $jumlahSebelum,
                'jumlah_sesudah' => $jumlahSebelum - $itemOutgoing->jumlah_keluar,
                'deskripsi' => 'Barang keluar (Approved): ' . $itemOutgoing->jumlah_keluar . ' unit untuk ' . ($itemOutgoing->keperluan ?? 'tidak disebutkan'),
            ]);
        });

        return back()->with('success', 'Permintaan barang keluar disetujui.');
    }

    public function reject(Request $request, ItemOutgoing $itemOutgoing)
    {
        $user = auth()->user();
        if (!$user->hasRole(['admin', 'kasub'])) {
            return back()->with('error', 'Akses ditolak.');
        }

        if ($itemOutgoing->status !== 'pending') {
            return back()->with('error', 'Data sudah diproses sebelumnya.');
        }

        $itemOutgoing->update(['status' => 'rejected']);

        return back()->with('success', 'Permintaan barang keluar ditolak.');
    }
}
