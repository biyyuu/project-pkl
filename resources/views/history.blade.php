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

            @if(session('success'))
                <div style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.25); padding: 14px 18px; border-radius: 10px; color: #4ade80; font-size: 13px; font-weight: 500; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25); padding: 14px 18px; border-radius: 10px; color: #f87171; font-size: 13px; font-weight: 500; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

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
                                    @role('admin')
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5); text-align: right;">Aksi</th>
                                    @endrole
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
                                    @role('admin')
                                    <td style="padding: 12px; text-align: right;">
                                        <form action="{{ route('history.destroy', $history) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan riwayat ini? Ini tidak mengembalikan stok barang.')" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Riwayat" style="background: none; border: none; color: #f87171; cursor: pointer; padding: 4px;">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18">
                                                    <polyline points="3 6 5 6 21 6"/>
                                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                    @endrole
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
