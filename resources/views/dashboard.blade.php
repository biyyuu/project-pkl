<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Kemenhan Pusdatin</title>
    <meta name="description" content="Dashboard Sistem Inventaris Kementerian Pertahanan Pusat Data dan Informasi">
    <link rel="icon" href="{{ asset('images/kemenhan-logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
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
                <a href="{{ route('item') }}" class="nav-item" id="nav-daftar-barang">
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
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                        <h1 style="margin-bottom: 0;">Halo, {{ $user->name }}!</h1>
                        @php
                            $roleLabels = [
                                'admin' => 'Admin',
                                'kasub' => 'Kasub',
                                'kabid' => 'Kabid',
                            ];
                            $roleColors = [
                                'admin' => 'background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); color: #f87171;',
                                'kasub' => 'background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399;',
                                'kabid' => 'background: rgba(251, 191, 36, 0.15); border: 1px solid rgba(251, 191, 36, 0.3); color: #fbbf24;',
                            ];
                            $label = $roleLabels[$user->role] ?? ucfirst($user->role);
                            $style = $roleColors[$user->role] ?? 'background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #ffffff;';
                        @endphp
                        <span style="font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; {{ $style }}">
                            {{ $label }}
                        </span>
                    </div>
                    <p>Siap memonitoring inventaris?</p>
                </div>
                <div class="header-right">
                    <div class="header-actions">
                        <button class="btn-export" id="btn-export" onclick="openExportModal()">
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
                                    <path d="M17 21v-2a4 4 0 0 0    
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
                    <div class="card-header" style="display: flex; align-items: center; justify-content: space-between;">
                        <span class="card-title">Demand Peminjaman by Grafik</span>
                        <select id="chartPeriodSelect" onchange="updateChartPeriod()" style="background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); border-radius: 6px; padding: 4px 8px; font-size: 12px; font-family: 'Inter', sans-serif; cursor:pointer; outline:none;">
                            <option value="harian" {{ $chartPeriod === 'harian' ? 'selected' : '' }}>Harian</option>
                            <option value="mingguan" {{ $chartPeriod === 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                            <option value="bulanan" {{ $chartPeriod === 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="6_bulan" {{ $chartPeriod === '6_bulan' ? 'selected' : '' }}>6 Bulan</option>
                        </select>
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

    <!-- ===== MODAL EXPORT ===== -->
    <style>
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center; justify-content: center;
            z-index: 999;
            opacity: 0; transition: opacity 0.25s ease;
        }
        .modal-overlay.show { opacity: 1; }
        .modal {
            background-color: #2a1f1c; padding: 24px; border-radius: 14px;
            width: 100%; max-width: 400px; border: 1px solid rgba(255,255,255,0.08);
            transform: translateY(10px); transition: transform 0.25s ease;
        }
        .modal-overlay.show .modal { transform: translateY(0); }
        
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-title { font-size: 18px; font-weight: 700; color: #fff; margin:0;}
        .modal-close { background: none; border: none; color: rgba(255,255,255,0.5); cursor: pointer; }
        .modal-close:hover { color: #fff; }
        
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; color: rgba(255,255,255,0.7); margin-bottom: 6px; }
        .form-control {
            width: 100%; padding: 10px 12px; background: rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1); border-radius: 8px;
            color: #fff; font-family: 'Inter', sans-serif; font-size: 14px; outline:none;
        }
        .form-control:focus { border-color: rgba(255,255,255,0.3); }
        .btn-submit {
            width: 100%; padding: 12px; background: #fbbf24; border: none;
            border-radius: 8px; color: #1a1210; font-weight: 600; cursor: pointer;
            margin-top: 10px; font-family: 'Inter', sans-serif;
        }
        .btn-submit:hover { background: #f59e0b; }
    </style>
    
    <div class="modal-overlay" id="modal-overlay-export">
        <div class="modal" id="modal-export">
            <div class="modal-header">
                <h2 class="modal-title">Export Laporan PDF</h2>
                <button class="modal-close" type="button" onclick="closeExportModal()">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('export.pdf') }}" method="GET" target="_blank" onsubmit="closeExportModal()">
                    <div class="form-group">
                        <label class="form-label">Pilih Bulan</label>
                        <select class="form-control" name="month" required>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Pilih Tahun</label>
                        <select class="form-control" name="year" required>
                            @foreach(range(now()->year, now()->subYears(5)->year) as $y)
                                <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-submit">Unduh PDF</button>
                </form>
            </div>
        </div>
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

        function updateChartPeriod() {
            const period = document.getElementById('chartPeriodSelect').value;
            const url = new URL(window.location.href);
            url.searchParams.set('chart_period', period);
            window.location.href = url.toString();
        }

        // ===== MODAL EXPORT =====
        function openExportModal() {
            const overlay = document.getElementById('modal-overlay-export');
            overlay.style.display = 'flex';
            requestAnimationFrame(() => overlay.classList.add('show'));
        }

        function closeExportModal() {
            const overlay = document.getElementById('modal-overlay-export');
            overlay.classList.remove('show');
            setTimeout(() => {
                overlay.style.display = 'none';
            }, 250);
        }
        
        // Close modal when clicking outside
        document.getElementById('modal-overlay-export').addEventListener('click', function(e) {
            if (e.target === this) closeExportModal();
        });
    </script>
</body>
</html>