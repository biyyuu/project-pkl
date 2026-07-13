<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\ItemOutgoing;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

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

        // --- Demand Peminjaman by Grafik ---
        $chartPeriod = $request->query('chart_period', '6_bulan');
        $demandLabels = [];
        $demandData = [];

        if ($chartPeriod === 'harian') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $demandLabels[] = $date->translatedFormat('d M');
                $demandData[] = ItemOutgoing::whereDate('tanggal_keluar', $date->format('Y-m-d'))
                    ->sum('jumlah_keluar');
            }
        } elseif ($chartPeriod === 'mingguan') {
            for ($i = 3; $i >= 0; $i--) {
                $start = now()->subWeeks($i)->startOfWeek();
                $end = now()->subWeeks($i)->endOfWeek();
                $demandLabels[] = $start->translatedFormat('d M') . ' - ' . $end->translatedFormat('d M');
                $demandData[] = ItemOutgoing::whereBetween('tanggal_keluar', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->sum('jumlah_keluar');
            }
        } elseif ($chartPeriod === 'bulanan') {
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $demandLabels[] = $date->translatedFormat('M Y');
                $demandData[] = ItemOutgoing::whereMonth('tanggal_keluar', $date->month)
                    ->whereYear('tanggal_keluar', $date->year)
                    ->sum('jumlah_keluar');
            }
        } else {
            // 6_bulan (default)
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $demandLabels[] = $date->translatedFormat('M Y');
                $demandData[] = ItemOutgoing::whereMonth('tanggal_keluar', $date->month)
                    ->whereYear('tanggal_keluar', $date->year)
                    ->sum('jumlah_keluar');
            }
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
            'chartPeriod',
            'demandLabels',
            'demandData',
            'daftarPeminjam',
            'barangSeringDipinjam',
            'barangTersedia',
            'historyPeminjaman',
        ));
    }

    public function exportPdf(Request $request)
    {
        $month = $request->query('month', now()->month);
        $year = $request->query('year', now()->year);

        // Fetch data for the selected month & year
        $outgoings = ItemOutgoing::with(['item', 'borrower', 'recorder'])
            ->whereMonth('tanggal_keluar', $month)
            ->whereYear('tanggal_keluar', $year)
            ->orderBy('tanggal_keluar', 'asc')
            ->get();

        $monthName = Carbon::create()->month((int)$month)->translatedFormat('F');
        
        // Let's create a base64 version of the kemenhan logo if exists
        $logoPath = public_path('images/kemenhan-logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $pdf = Pdf::loadView('pdf.laporan', compact('outgoings', 'monthName', 'year', 'logoBase64'))
                ->setPaper('a4', 'landscape');

        return $pdf->download("Laporan_Peminjaman_Inventaris_{$monthName}_{$year}.pdf");
    }

    public function history(Request $request)
    {
        $user = auth()->user();
        
        $query = \App\Models\ItemHistory::with(['item', 'user'])->orderByDesc('created_at');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%");
            });
        }
        
        $histories = $query->paginate(15)->withQueryString();
        
        return view('history', compact('user', 'histories'));
    }
}