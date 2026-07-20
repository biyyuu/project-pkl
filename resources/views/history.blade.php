<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Peminjaman - Inventaris</title>
    <link rel="icon" href="{{ asset('images/kemenhan-logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        /* Override the global overflow:hidden so this page can scroll */
        html, body {
            overflow: hidden;
        }

        .main-content {
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        /* ===== HISTORY CARD ===== */
        .history-card {
            background-color: #2a1f1c;
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,0.04);
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
        }

        .history-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 22px 22px 0 22px;
        }

        .history-card-title {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: rgba(255,255,255,0.5);
        }

        /* ===== FILTER BAR ===== */
        .filter-bar {
            padding: 16px 22px;
        }

        .filter-form {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            flex-wrap: wrap;
            background: rgba(255,255,255,0.02);
            padding: 16px;
            border-radius: 4px;
            border: 1px solid rgba(255,255,255,0.04);
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .filter-label {
            font-size: 11px;
            font-weight: 600;
            color: rgba(255,255,255,0.55);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-input {
            background: rgba(0,0,0,0.20);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 4px;
            padding: 9px 14px;
            color: #ffffff;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            outline: none;
            transition: all 0.2s ease;
            color-scheme: dark;
        }

        .filter-input:focus {
            border-color: rgba(255,255,255,0.18);
            background: rgba(255,255,255,0.06);
        }

        .filter-input::placeholder {
            color: rgba(255,255,255,0.25);
        }

        .filter-input-search { width: 220px; }
        .filter-input-date { width: 160px; }

        .btn-filter-submit {
            background-color: #5c1a1a;
            border: 1px solid #5c1a1a;
            color: #ffffff;
            padding: 9px 18px;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            height: 37px;
            transition: all 0.2s ease;
        }

        .btn-filter-submit:hover {
            background-color: #7a2323;
        }

        .btn-filter-reset {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7);
            padding: 9px 14px;
            border-radius: 4px;
            cursor: pointer;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 37px;
            transition: all 0.2s ease;
        }

        .btn-filter-reset:hover {
            background: rgba(255,255,255,0.08);
            color: #ffffff;
        }

        /* ===== SCROLLABLE TABLE AREA ===== */
        .table-scroll-area {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            margin: 0 22px 22px 22px;
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 4px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        .table-scroll-area::-webkit-scrollbar { width: 5px; }
        .table-scroll-area::-webkit-scrollbar-track { background: transparent; }
        .table-scroll-area::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }

        /* ===== DATA TABLE ===== */
        .history-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .history-table thead th {
            position: sticky;
            top: 0;
            background: #2a1f1c;
            z-index: 10;
            padding: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255,255,255,0.35);
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .history-table tbody td {
            padding: 12px;
            font-size: 13px;
            font-weight: 400;
            color: rgba(255,255,255,0.75);
            border-bottom: 1px solid rgba(255,255,255,0.04);
            vertical-align: middle;
        }

        .history-table tbody tr:last-child td {
            border-bottom: none;
        }

        .history-table tbody tr {
            transition: background-color 0.15s ease;
        }

        .history-table tbody tr:hover {
            background-color: rgba(255,255,255,0.02);
        }

        /* ===== ACTION BADGES ===== */
        .action-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .action-badge.tambah { background: rgba(34, 197, 94, 0.12); color: #4ade80; }
        .action-badge.edit { background: rgba(59, 130, 246, 0.12); color: #60a5fa; }
        .action-badge.hapus { background: rgba(239, 68, 68, 0.12); color: #f87171; }
        .action-badge.keluar { background: rgba(251, 191, 36, 0.12); color: #fbbf24; }
        .action-badge.selesai { background: rgba(16, 185, 129, 0.12); color: #34d399; }
        .action-badge.ditolak { background: rgba(239, 68, 68, 0.12); color: #f87171; }

        /* ===== DELETE BUTTON ===== */
        .btn-delete-history {
            background: none;
            border: none;
            color: rgba(255,255,255,0.3);
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: all 0.2s ease;
            display: inline-flex;
        }

        .btn-delete-history:hover {
            color: #f87171;
            background: rgba(248,113,113,0.08);
        }

        /* ===== EMPTY STATE ===== */
        .history-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            color: rgba(255,255,255,0.3);
        }

        .history-empty svg {
            width: 48px;
            height: 48px;
            margin-bottom: 12px;
            opacity: 0.25;
        }

        .history-empty p {
            font-size: 14px;
            font-weight: 400;
        }

        .history-empty .empty-sub {
            font-size: 12px;
            color: rgba(255,255,255,0.2);
            margin-top: 4px;
        }

        /* ===== ALERT ===== */
        .alert-history {
            padding: 14px 18px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-history.success {
            background: rgba(34, 197, 94, 0.12);
            border: 1px solid rgba(34, 197, 94, 0.25);
            color: #4ade80;
        }

        .alert-history.error {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #f87171;
        }

        /* ===== RESULT COUNT ===== */
        .result-count {
            font-size: 12px;
            color: rgba(255,255,255,0.35);
            padding: 0 22px 12px 22px;
        }
    </style>
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
                <div class="alert-history success">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-history error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="history-card">
                <div class="history-card-header">
                    <span class="history-card-title">Riwayat Aktivitas</span>
                </div>

                <!-- FILTER BAR -->
                <div class="filter-bar">
                    <form action="{{ route('history.index') }}" method="GET" class="filter-form">
                        <div class="filter-group">
                            <label class="filter-label">Cari Barang</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..." class="filter-input filter-input-search">
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="filter-input filter-input-date">
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Tanggal Selesai</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="filter-input filter-input-date">
                        </div>

                        <div style="display: flex; gap: 8px;">
                            <button type="submit" class="btn-filter-submit">Filter</button>
                            @if(request()->filled('search') || request()->filled('start_date') || request()->filled('end_date'))
                                <a href="{{ route('history.index') }}" class="btn-filter-reset">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>

                @if($histories->isEmpty())
                    <div class="history-empty">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        <p>Belum ada riwayat tercatat.</p>
                        <span class="empty-sub">Aktivitas barang akan muncul di sini.</span>
                    </div>
                @else
                    <div class="result-count">
                        Menampilkan {{ $histories->count() }} riwayat
                    </div>
                    <div class="table-scroll-area">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>User/Perekam</th>
                                    <th>Aksi</th>
                                    <th>Barang</th>
                                    <th>Stok Berubah</th>
                                    <th>Deskripsi</th>
                                    @role('admin')
                                    <th style="text-align: right;">Aksi</th>
                                    @endrole
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($histories as $history)
                                <tr>
                                    <td style="white-space: nowrap;">{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $history->user->name ?? '-' }}</td>
                                    <td>
                                        <span class="action-badge {{ $history->action }}">{{ $history->action }}</span>
                                    </td>
                                    <td>{{ $history->item->nama_barang ?? '-' }}</td>
                                    <td style="white-space: nowrap;">
                                        {{ $history->jumlah_sebelum ?? '-' }} &rarr; {{ $history->jumlah_sesudah ?? '-' }}
                                    </td>
                                    <td style="font-size: 12px; max-width: 280px;">{{ $history->deskripsi ?? '-' }}</td>
                                    @role('admin')
                                    <td style="text-align: right;">
                                        <form action="{{ route('history.destroy', $history) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan riwayat ini? Ini tidak mengembalikan stok barang.')" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus Riwayat" class="btn-delete-history">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="16" height="16">
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
                @endif
            </div>
        </main>
    </div>
</body>
</html>
