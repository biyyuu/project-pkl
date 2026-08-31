<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\ItemOutgoing;
use App\Models\SstockBrg;
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
        $totalPeminjaman = ItemOutgoing::where('status', 'approved')->count();
        $totalPeminjamanBulanIni = ItemOutgoing::where('status', 'approved')
            ->whereMonth('tanggal_keluar', now()->month)
            ->whereYear('tanggal_keluar', now()->year)
            ->count();

        // --- Total Barang ---
        $totalBarang = SstockBrg::count();
        $totalStok = SstockBrg::sum('stock');

        // --- Total Peminjam (Unique Borrowers) ---
        $totalPeminjam = \App\Models\ItemOutgoing::where('status', 'approved')->distinct('borrower_id')->count('borrower_id');

        // --- Barang Kondisi Baik ---
        $barangBaik = SstockBrg::whereRaw("LOWER(TRIM(COALESCE(kondisi, ''))) = ?", ['baik'])->count();

        // --- Demand Peminjaman by Grafik ---
        $chartPeriod = $request->query('chart_period', '6_bulan');
        $demandLabels = [];
        $demandData = [];

        if ($chartPeriod === 'harian') {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $demandLabels[] = $date->translatedFormat('d M');
                $demandData[] = ItemOutgoing::where('status', 'approved')
                    ->whereDate('tanggal_keluar', $date->format('Y-m-d'))
                    ->sum('jumlah_keluar');
            }
        } elseif ($chartPeriod === 'mingguan') {
            for ($i = 3; $i >= 0; $i--) {
                $start = now()->subWeeks($i)->startOfWeek();
                $end = now()->subWeeks($i)->endOfWeek();
                $demandLabels[] = $start->translatedFormat('d M') . ' - ' . $end->translatedFormat('d M');
                $demandData[] = ItemOutgoing::where('status', 'approved')
                    ->whereBetween('tanggal_keluar', [$start->format('Y-m-d'), $end->format('Y-m-d')])
                    ->sum('jumlah_keluar');
            }
        } elseif ($chartPeriod === 'bulanan') {
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $demandLabels[] = $date->translatedFormat('M Y');
                $demandData[] = ItemOutgoing::where('status', 'approved')
                    ->whereMonth('tanggal_keluar', $date->month)
                    ->whereYear('tanggal_keluar', $date->year)
                    ->sum('jumlah_keluar');
            }
        } else {
            // 6_bulan (default)
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $demandLabels[] = $date->translatedFormat('M Y');
                $demandData[] = ItemOutgoing::where('status', 'approved')
                    ->whereMonth('tanggal_keluar', $date->month)
                    ->whereYear('tanggal_keluar', $date->year)
                    ->sum('jumlah_keluar');
            }
        }

        // --- Daftar Peminjam (latest outgoings with borrower info) ---
        $daftarPeminjam = ItemOutgoing::where('status', 'approved')
            ->with(['borrower', 'item'])
            ->orderByDesc('tanggal_keluar')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // --- Barang Paling Sering Dipinjam ---
        $barangSeringDipinjam = ItemOutgoing::where('status', 'approved')
            ->select('item_id', DB::raw('SUM(jumlah_keluar) as total_keluar'), DB::raw('COUNT(*) as frekuensi'))
            ->groupBy('item_id')
            ->orderByDesc('total_keluar')
            ->limit(5)
            ->with(['item'])
            ->get();

        // --- List Barang Tersedia ---
        $barangTersedia = SstockBrg::where('stock', '>', 0)
            ->whereRaw("LOWER(TRIM(COALESCE(kondisi, ''))) = ?", ['baik'])
            ->orderByDesc('stock')
            ->limit(10)
            ->get();

        // --- History Barang Keluar & Masuk (Recent Activity) ---
        $historyPeminjaman = ItemOutgoing::with(['item', 'borrower'])
            ->whereIn('status', ['approved', 'completed'])
            ->orderByDesc('updated_at')
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
            ->where('status', 'approved')
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
        
        $query = \App\Models\ItemHistory::with(['item', 'user'])
            ->whereIn('action', ['keluar', 'selesai'])
            ->orderByDesc('created_at');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            });
        }

        // Filter by action type (keluar / selesai)
        if ($request->filled('tipe')) {
            $query->where('action', $request->tipe);
        }

        // Date range filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } elseif ($request->filled('start_date')) {
            $query->where('created_at', '>=', $request->start_date . ' 00:00:00');
        } elseif ($request->filled('end_date')) {
            $query->where('created_at', '<=', $request->end_date . ' 23:59:59');
        }
        
        $histories = $query->paginate(15)->withQueryString();
        
        return view('history', compact('user', 'histories'));
    }

    public function destroyHistory(ItemHistory $history)
    {
        // Only admin can delete history
        if (!auth()->user()->hasRole('admin')) {
            return back()->with('error', 'Akses ditolak.');
        }

        $history->delete();
        return back()->with('success', 'Catatan riwayat berhasil dihapus.');
    }

    public function exportBarangPdf(Request $request)
    {
        $month = $request->query('month', now()->month);
        $year = $request->query('year', now()->year);

        $items = SstockBrg::orderBy('nama', 'asc')->get()->map(function (SstockBrg $item) {
            return (object) [
                'no_inventaris' => $item->noinven,
                'nama_barang' => $item->nama,
                'merk' => $item->merk,
                'serial_number' => $item->snumber,
                'jumlah' => $item->stock,
                'nama_pengadaan' => $item->pengadaan,
                'tahun_pengadaan' => $item->lokasi,
                'kondisi_barang' => $item->kondisi,
                'keterangan' => $item->keterangan,
            ];
        });

        $monthName = Carbon::create()->month((int) $month)->translatedFormat('F');

        $logoPath = public_path('images/kemenhan-logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $pdf = Pdf::loadView('pdf.laporan-barang', compact('items', 'monthName', 'year', 'logoBase64'))
                ->setPaper('a4', 'landscape');

        return $pdf->download("Laporan_Daftar_Barang_Inventaris_{$monthName}_{$year}.pdf");
    }

    public function exportHistoryPdf(Request $request)
    {
        $month = $request->query('month', now()->month);
        $year = $request->query('year', now()->year);

        // Barang keluar (approved) in the selected month
        $outgoings = ItemOutgoing::with(['item', 'borrower'])
            ->where('status', 'approved')
            ->whereMonth('tanggal_keluar', $month)
            ->whereYear('tanggal_keluar', $year)
            ->orderBy('tanggal_keluar', 'asc')
            ->get();

        // Barang masuk/dikembalikan (completed) in the selected month
        $returns = ItemOutgoing::with(['item', 'borrower'])
            ->where('status', 'completed')
            ->whereMonth('tanggal_kembali', $month)
            ->whereYear('tanggal_kembali', $year)
            ->orderBy('tanggal_kembali', 'asc')
            ->get();

        $monthName = Carbon::create()->month((int) $month)->translatedFormat('F');

        $logoPath = public_path('images/kemenhan-logo.png');
        $logoBase64 = '';
        if (file_exists($logoPath)) {
            $logoData = file_get_contents($logoPath);
            $logoBase64 = 'data:image/png;base64,' . base64_encode($logoData);
        }

        $pdf = Pdf::loadView('pdf.laporan-history', compact('outgoings', 'returns', 'monthName', 'year', 'logoBase64'))
                ->setPaper('a4', 'landscape');

        return $pdf->download("Laporan_History_Barang_Keluar_Masuk_{$monthName}_{$year}.pdf");
    }
}
