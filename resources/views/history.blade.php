<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Peminjaman - Inventaris</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/itemlist.css') }}">
</head>
<body>
    <div class="app-layout">
        @include('components.sidebar')

        <main class="main-content">
            <div class="header">
                <div class="header-left">
                    <h1>History Peminjaman Barang</h1>
                    <p>Riwayat seluruh aktivitas terkait stok barang.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <span class="card-title">Riwayat Aktivitas</span>
                </div>

                @if($histories->isEmpty())
                    <div class="empty-state" style="padding: 40px; text-align: center; color: rgba(255,255,255,0.4);">
                        <p>Belum ada riwayat tercatat.</p>
                    </div>
                @else
                    <div class="scroll-area">
                        <table class="data-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Waktu</th>
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">User/Perekam</th>
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Aksi</th>
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Barang</th>
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Stok Berubah</th>
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($histories as $history)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 12px; font-size: 14px;">{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                    <td style="padding: 12px; font-size: 14px;">{{ $history->user->name ?? '-' }}</td>
                                    <td style="padding: 12px; font-size: 14px;">
                                        <span class="status-badge {{ $history->action }}" style="padding: 4px 8px; border-radius: 4px; font-size:11px; font-weight: 600; text-transform: uppercase;">{{ $history->action }}</span>
                                    </td>
                                    <td style="padding: 12px; font-size: 14px;">{{ $history->item->nama_barang ?? '-' }}</td>
                                    <td style="padding: 12px; font-size: 14px;">
                                        {{ $history->jumlah_sebelum ?? '-' }} &rarr; {{ $history->jumlah_sesudah ?? '-' }}
                                    </td>
                                    <td style="padding: 12px; font-size: 14px;">{{ $history->deskripsi ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top: 15px;">
                        {{ $histories->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
