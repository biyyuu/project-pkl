<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\ItemOutgoing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // --- Total Peminjaman (Barang Keluar) ---
        $totalPeminjaman = ItemOutgoing::count();
        $totalPeminjamanBulanIni = ItemOutgoing::whereMonth('tanggal_keluar', now()->month)
            ->whereYear('tanggal_keluar', now()->year)
            ->count();

        // --- Total Barang ---
        $totalBarang = Item::count();
        $totalStok = Item::sum('jumlah');

        // --- Total User/Peminjam ---
        $totalPeminjam = User::count();

        // --- Barang Kondisi Baik ---
        $barangBaik = Item::where('kondisi_barang', 'baik')->count();

        // --- Demand Peminjaman by Grafik (last 6 months) ---
        $demandLabels = [];
        $demandData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $demandLabels[] = $date->translatedFormat('M Y');
            $demandData[] = ItemOutgoing::whereMonth('tanggal_keluar', $date->month)
                ->whereYear('tanggal_keluar', $date->year)
                ->sum('jumlah_keluar');
        }

        // --- Daftar Peminjam (latest outgoings with borrower info) ---
        $daftarPeminjam = ItemOutgoing::with(['borrower', 'item'])
            ->orderByDesc('tanggal_keluar')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // --- Barang Paling Sering Dipinjam ---
        $barangSeringDipinjam = ItemOutgoing::select('item_id', DB::raw('SUM(jumlah_keluar) as total_keluar'), DB::raw('COUNT(*) as frekuensi'))
            ->groupBy('item_id')
            ->orderByDesc('total_keluar')
            ->limit(5)
            ->with('item')
            ->get();

        // --- List Barang Tersedia ---
        $barangTersedia = Item::where('jumlah', '>', 0)
            ->where('kondisi_barang', 'baik')
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get();

        // --- History Peminjaman (Recent Activity) ---
        $historyPeminjaman = ItemHistory::with(['item', 'user'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'user',
            'totalPeminjaman',
            'totalPeminjamanBulanIni',
            'totalBarang',
            'totalStok',
            'totalPeminjam',
            'barangBaik',
            'demandLabels',
            'demandData',
            'daftarPeminjam',
            'barangSeringDipinjam',
            'barangTersedia',
            'historyPeminjaman',
        ));
    }
}