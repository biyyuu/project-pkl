<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kemenhan Pusdatin</title>
    <meta name="description" content="Dashboard Sistem Inventaris Kementerian Pertahanan Pusat Data dan Informasi">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <style>
        /* ===== RESET & BASE ===== */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #1a1210;
            color: #ffffff;
        }

        /* ===== LAYOUT ===== */
        .app-layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 240px;
            background-color: #1a1210;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid rgba(255,255,255,0.06);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 20px 20px 28px 20px;
        }

        .sidebar-brand img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            flex-shrink: 0;
        }

        .sidebar-brand-text {
            font-size: 13px;
            font-weight: 600;
            line-height: 1.3;
            color: rgba(255,255,255,0.9);
        }

        .sidebar-nav {
            flex: 1;
            padding: 0 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 10px;
            text-decoration: none;
            color: rgba(255,255,255,0.55);
            font-size: 14px;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            color: rgba(255,255,255,0.85);
            background-color: rgba(255,255,255,0.05);
        }

        .nav-item.active {
            color: #ffffff;
            background-color: rgba(255,255,255,0.08);
        }

        .nav-item svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .nav-item.active svg {
            opacity: 1;
        }

        .sidebar-footer {
            padding: 16px 12px 20px 12px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 16px;
            border-radius: 10px;
            background: none;
            border: none;
            color: rgba(255,255,255,0.55);
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            width: 100%;
            transition: all 0.2s ease;
        }

        .logout-btn:hover {
            color: #f87171;
            background-color: rgba(248,113,113,0.08);
        }

        .logout-btn svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            margin-left: 240px;
            padding: 24px 28px 40px 28px;
            min-height: 100vh;
            overflow-y: auto;
        }

        /* ===== HEADER ===== */
        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 28px;
        }

        .header-left h1 {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .header-left p {
            font-size: 14px;
            color: rgba(255,255,255,0.5);
            font-weight: 400;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-direction: column;
            align-items: flex-end;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn-filter,
        .btn-export {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-filter {
            background: #ffffff;
            border: 1px solid #ffffff;
            color: #1a1210;
        }

        .btn-filter:hover {
            border-color: rgba(255,255,255, 0.9);
            background: rgba(255,255,255, 0.9);
        }

        .btn-export {
            background-color: #5c1a1a;
            border: 1px solid #5c1a1a;
            color: #ffffff;
        }

        .btn-export:hover {
            background-color: #7a2323;
        }

        .btn-filter svg,
        .btn-export svg {
            width: 16px;
            height: 16px;
        }

        .header-date {
            font-size: 12px;
            color: rgba(255,255,255,0.4);
            font-weight: 400;
            margin-top: 6px;
        }

        /* ===== DASHBOARD GRID ===== */
        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        /* ===== CARD ===== */
        .card {
            background-color: #2a1f1c;
            border-radius: 14px;
            padding: 22px;
            border: 1px solid rgba(255,255,255,0.04);
            transition: border-color 0.3s ease;
        }

        .card:hover {
            border-color: rgba(255,255,255,0.08);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 18px;
        }

        .card-title {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: rgba(255,255,255,0.5);
        }

        .card-badge {
            font-size: 11px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.5);
        }

        /* ===== STAT CARDS (ROW 1 LEFT) ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .stat-card {
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            padding: 18px 16px;
            border: 1px solid rgba(255,255,255,0.04);
        }

        .stat-card .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .stat-card .stat-icon svg {
            width: 18px;
            height: 18px;
        }

        .stat-card .stat-icon.peminjaman {
            background: rgba(139, 92, 246, 0.15);
            color: #a78bfa;
        }

        .stat-card .stat-icon.barang {
            background: rgba(59, 130, 246, 0.15);
            color: #60a5fa;
        }

        .stat-card .stat-icon.peminjam {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
        }

        .stat-card .stat-icon.kondisi {
            background: rgba(251, 191, 36, 0.15);
            color: #fbbf24;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 12px;
            font-weight: 500;
            color: rgba(255,255,255,0.45);
        }

        .stat-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.3);
            margin-top: 4px;
        }

        /* ===== CHART ===== */
        .chart-container {
            position: relative;
            height: 220px;
        }

        /* ===== TABLE ===== */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead th {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: rgba(255,255,255,0.35);
            padding: 0 0 12px 0;
            text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .data-table tbody td {
            font-size: 13px;
            font-weight: 400;
            color: rgba(255,255,255,0.75);
            padding: 11px 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-table .item-name {
            font-weight: 500;
            color: rgba(255,255,255,0.9);
        }

        /* ===== STATUS BADGE ===== */
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-badge.baik {
            background: rgba(34, 197, 94, 0.12);
            color: #4ade80;
        }

        .status-badge.rusak_ringan {
            background: rgba(251, 191, 36, 0.12);
            color: #fbbf24;
        }

        .status-badge.rusak_berat {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
        }

        .status-badge.hilang {
            background: rgba(156, 163, 175, 0.12);
            color: #9ca3af;
        }

        /* Action badges */
        .action-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .action-badge.tambah {
            background: rgba(34, 197, 94, 0.12);
            color: #4ade80;
        }

        .action-badge.edit {
            background: rgba(59, 130, 246, 0.12);
            color: #60a5fa;
        }

        .action-badge.hapus {
            background: rgba(239, 68, 68, 0.12);
            color: #f87171;
        }

        .action-badge.keluar {
            background: rgba(251, 191, 36, 0.12);
            color: #fbbf24;
        }

        /* ===== RANKED LIST ===== */
        .ranked-list {
            list-style: none;
        }

        .ranked-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }

        .ranked-item:last-child {
            border-bottom: none;
        }

        .rank-number {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: rgba(139, 92, 246, 0.12);
            color: #a78bfa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        .rank-number.rank-1 {
            background: rgba(251, 191, 36, 0.15);
            color: #fbbf24;
        }

        .rank-number.rank-2 {
            background: rgba(156, 163, 175, 0.12);
            color: #9ca3af;
        }

        .rank-number.rank-3 {
            background: rgba(180, 120, 60, 0.15);
            color: #d4a574;
        }

        .ranked-info {
            flex: 1;
            min-width: 0;
        }

        .ranked-name {
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,0.85);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .ranked-meta {
            font-size: 11px;
            color: rgba(255,255,255,0.35);
            margin-top: 2px;
        }

        .ranked-value {
            font-size: 14px;
            font-weight: 700;
            color: rgba(255,255,255,0.8);
            flex-shrink: 0;
        }

        /* ===== AVAILABLE ITEM BAR ===== */
        .avail-bar-track {
            width: 60px;
            height: 6px;
            background: rgba(255,255,255,0.06);
            border-radius: 3px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .avail-bar-fill {
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(90deg, #4ade80, #22c55e);
        }

        /* ===== EMPTY STATE ===== */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            color: rgba(255,255,255,0.3);
        }

        .empty-state svg {
            width: 40px;
            height: 40px;
            margin-bottom: 12px;
            opacity: 0.3;
        }

        .empty-state p {
            font-size: 13px;
            font-weight: 400;
        }

        /* ===== SCROLL AREA ===== */
        .scroll-area {
            max-height: 280px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }

        .scroll-area::-webkit-scrollbar {
            width: 4px;
        }

        .scroll-area::-webkit-scrollbar-track {
            background: transparent;
        }

        .scroll-area::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 2px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }

            .main-content {
                margin-left: 0;
                padding: 16px;
            }

            .header {
                flex-direction: column;
                gap: 16px;
            }

            .header-right {
                align-items: flex-start;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="app-layout">
        <!-- ===== SIDEBAR ===== -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <img src="{{ asset('images/kemenhan-logo.png') }}" alt="Logo">
                <span class="sidebar-brand-text">Inventaris<br>Kemenhan Pusdatin</span>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item active" id="nav-dashboard">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Dashboard
                </a>
                <a href="#" class="nav-item" id="nav-daftar-barang">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="8" y1="6" x2="21" y2="6"/>
                        <line x1="8" y1="12" x2="21" y2="12"/>
                        <line x1="8" y1="18" x2="21" y2="18"/>
                        <line x1="3" y1="6" x2="3.01" y2="6"/>
                        <line x1="3" y1="12" x2="3.01" y2="12"/>
                        <line x1="3" y1="18" x2="3.01" y2="18"/>
                    </svg>
                    Daftar Barang
                </a>
                <a href="{{ route('item-outgoing.index') }}" class="nav-item" id="nav-barang-keluar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Barang Keluar
                </a>
            </nav>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <button type="submit" class="logout-btn" id="logout-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1-2-2h4"/>
                            <polyline points="16 17 21 12 16 7"/>
                            <line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- ===== MAIN CONTENT ===== -->
        <main class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1>Halo, {{ $user->name }}!</h1>
                    <p>Siap memonitoring inventaris?</p>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="btn-filter" id="btn-filter">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            This Month
                        </button>
                        <button class="btn-export" id="btn-export">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="7 10 12 15 17 10"/>
                                <line x1="12" y1="15" x2="12" y2="3"/>
                            </svg>
                            Export
                        </button>
                    </div>
                    <span class="header-date">{{ now()->translatedFormat('l, d F Y') }}</span>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="dashboard-grid">

                <!-- ===== ROW 1: Total Pinjaman + Demand Chart ===== -->
                <!-- Total Stats -->
                <div class="card" id="card-total">
                    <div class="card-header">
                        <span class="card-title">Total Pinjaman</span>
                        <span class="card-badge">Bulan Ini: {{ $totalPeminjamanBulanIni }}</span>
                    </div>
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon peminjaman">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                            </div>
                            <div class="stat-value">{{ $totalPeminjaman }}</div>
                            <div class="stat-label">Total Peminjaman</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon barang">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                                    <line x1="12" y1="22.08" x2="12" y2="12"/>
                                </svg>
                            </div>
                            <div class="stat-value">{{ $totalBarang }}</div>
                            <div class="stat-label">Total Barang</div>
                            <div class="stat-sub">{{ $totalStok }} unit stok</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon peminjam">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                    <circle cx="9" cy="7" r="4"/>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </div>
                            <div class="stat-value">{{ $totalPeminjam }}</div>
                            <div class="stat-label">Total Peminjam</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-icon kondisi">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                    <polyline points="22 4 12 14.01 9 11.01"/>
                                </svg>
                            </div>
                            <div class="stat-value">{{ $barangBaik }}</div>
                            <div class="stat-label">Kondisi Baik</div>
                        </div>
                    </div>
                </div>

                <!-- Demand Chart -->
                <div class="card" id="card-demand">
                    <div class="card-header">
                        <span class="card-title">Demand Peminjaman by Grafik</span>
                        <span class="card-badge">6 Bulan Terakhir</span>
                    </div>
                    <div class="chart-container">
                        <canvas id="demandChart"></canvas>
                    </div>
                </div>

                <!-- ===== ROW 2: Daftar Peminjam + Barang Paling Sering Dipinjam ===== -->
                <!-- Daftar Peminjam -->
                <div class="card" id="card-peminjam">
                    <div class="card-header">
                        <span class="card-title">Daftar Peminjam</span>
                        <span class="card-badge">{{ $daftarPeminjam->count() }} terbaru</span>
                    </div>
                    @if($daftarPeminjam->isEmpty())
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                            </svg>
                            <p>Belum ada data peminjam</p>
                        </div>
                    @else
                        <div class="scroll-area">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Peminjam</th>
                                        <th>Barang</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($daftarPeminjam as $pinjam)
                                    <tr>
                                        <td class="item-name">{{ $pinjam->borrower->nama ?? '-' }}</td>
                                        <td>{{ $pinjam->item->nama_barang ?? '-' }}</td>
                                        <td>{{ $pinjam->jumlah_keluar }}</td>
                                        <td>{{ $pinjam->tanggal_keluar->format('d/m/Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- Barang Paling Sering Dipinjam -->
                <div class="card" id="card-sering-dipinjam">
                    <div class="card-header">
                        <span class="card-title">Barang Paling Sering Dipinjam</span>
                        <span class="card-badge">Top 5</span>
                    </div>
                    @if($barangSeringDipinjam->isEmpty())
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                            <p>Belum ada data</p>
                        </div>
                    @else
                        <ul class="ranked-list">
                            @foreach($barangSeringDipinjam as $index => $top)
                            <li class="ranked-item">
                                <span class="rank-number {{ $index < 3 ? 'rank-'.($index+1) : '' }}">{{ $index + 1 }}</span>
                                <div class="ranked-info">
                                    <div class="ranked-name">{{ $top->item->nama_barang ?? '-' }}</div>
                                    <div class="ranked-meta">{{ $top->frekuensi }}x dipinjam</div>
                                </div>
                                <span class="ranked-value">{{ $top->total_keluar }}</span>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- ===== ROW 3: List Barang Tersedia + History Peminjaman ===== -->
                <!-- List Barang Tersedia -->
                <div class="card" id="card-barang-tersedia">
                    <div class="card-header">
                        <span class="card-title">List Barang Tersedia</span>
                        <span class="card-badge">Kondisi Baik</span>
                    </div>
                    @if($barangTersedia->isEmpty())
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            </svg>
                            <p>Belum ada barang tersedia</p>
                        </div>
                    @else
                        <div class="scroll-area">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>No. Inventaris</th>
                                        <th>Nama Barang</th>
                                        <th>Merk</th>
                                        <th>Stok</th>
                                        <th>Kondisi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($barangTersedia as $barang)
                                    <tr>
                                        <td style="font-family: monospace; font-size: 12px;">{{ $barang->no_inventaris }}</td>
                                        <td class="item-name">{{ $barang->nama_barang }}</td>
                                        <td>{{ $barang->merk ?? '-' }}</td>
                                        <td>
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                {{ $barang->jumlah }}
                                                <div class="avail-bar-track">
                                                    <div class="avail-bar-fill" style="width: {{ min(100, $barang->jumlah * 10) }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="status-badge {{ $barang->kondisi_barang }}">{{ str_replace('_', ' ', $barang->kondisi_barang) }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <!-- History Peminjaman -->
                <div class="card" id="card-history">
                    <div class="card-header">
                        <span class="card-title">History Peminjaman</span>
                        <span class="card-badge">Aktivitas Terbaru</span>
                    </div>
                    @if($historyPeminjaman->isEmpty())
                        <div class="empty-state">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            <p>Belum ada riwayat aktivitas</p>
                        </div>
                    @else
                        <div class="scroll-area">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Aksi</th>
                                        <th>Barang</th>
                                        <th>User</th>
                                        <th>Qty</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($historyPeminjaman as $history)
                                    <tr>
                                        <td><span class="action-badge {{ $history->action }}">{{ ucfirst($history->action) }}</span></td>
                                        <td class="item-name">{{ $history->item->nama_barang ?? '-' }}</td>
                                        <td>{{ $history->user->name ?? '-' }}</td>
                                        <td>
                                            @if($history->jumlah_sebelum !== null && $history->jumlah_sesudah !== null)
                                                {{ $history->jumlah_sebelum }} → {{ $history->jumlah_sesudah }}
                                            @elseif($history->jumlah_sesudah !== null)
                                                {{ $history->jumlah_sesudah }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td style="font-size: 12px; color: rgba(255,255,255,0.4);">{{ $history->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div><!-- end dashboard-grid -->
        </main>
    </div>

    <!-- ===== CHART JS ===== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('demandChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($demandLabels),
                        datasets: [{
                            label: 'Jumlah Barang Keluar',
                            data: @json($demandData),
                            backgroundColor: 'rgba(139, 92, 246, 0.35)',
                            borderColor: 'rgba(139, 92, 246, 0.8)',
                            borderWidth: 1,
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: '#2a1f1c',
                                titleColor: '#ffffff',
                                bodyColor: 'rgba(255,255,255,0.7)',
                                borderColor: 'rgba(255,255,255,0.1)',
                                borderWidth: 1,
                                padding: 12,
                                cornerRadius: 8,
                                titleFont: { family: 'Inter', weight: '600' },
                                bodyFont: { family: 'Inter' },
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false,
                                },
                                ticks: {
                                    color: 'rgba(255,255,255,0.35)',
                                    font: { family: 'Inter', size: 11 }
                                },
                                border: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(255,255,255,0.04)',
                                },
                                ticks: {
                                    color: 'rgba(255,255,255,0.35)',
                                    font: { family: 'Inter', size: 11 },
                                    precision: 0
                                },
                                border: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>