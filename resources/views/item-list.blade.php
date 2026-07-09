<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang - Inventaris Kemhan Pusdatin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/itemlist.css') }}">
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
                <a href="{{ route('dashboard') }}" class="nav-item" id="nav-dashboard">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('item') }}" class="nav-item active" id="nav-daftar-barang">
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
                <a href="#" class="nav-item" id="nav-barang-keluar">
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
            <div class="header">
                <div class="header-left">
                    <h1>Daftar Barang</h1>
                    <p>Siap memonitoring inventaris?</p>
                </div>
                <button class="btn-export" id="btn-tambah-barang">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Barang
                </button>
            </div>

            <!-- Flash Success Message -->
            @if(session('success'))
                <div class="alert-success" style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.25); padding: 14px 18px; border-radius: 10px; color: #4ade80; font-size: 13px; font-weight: 500; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; flex-shrink: 0;">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Flash Validation Errors -->
            @if ($errors->any())
                <div class="alert-danger" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25); padding: 14px 18px; border-radius: 10px; color: #f87171; font-size: 13px; font-weight: 500; margin-bottom: 20px; display: flex; flex-direction: column; gap: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; flex-shrink: 0;">
                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>Terdapat kesalahan pengisian data:</span>
                    </div>
                    <ul style="padding-left: 28px; margin: 0; font-size: 12px; color: rgba(255,255,255,0.7);">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <span class="card-title">Semua Barang</span>
                    <span class="card-badge">Total: {{ $items->count() }} barang</span>
                </div>
                
                @if($items->isEmpty())
                    <div class="empty-state" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 20px; color: rgba(255,255,255,0.3);">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width: 48px; height: 48px; margin-bottom: 12px; opacity: 0.3;">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        </svg>
                        <p>Belum ada data barang</p>
                    </div>
                @else
                    <div class="scroll-area" style="max-height: 520px;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>No. Inventaris</th>
                                    <th>Nama Barang</th>
                                    <th>Merk</th>
                                    <th>No. Seri</th>
                                    <th>Jumlah</th>
                                    <th>Pengadaan</th>
                                    <th>Tahun</th>
                                    <th>Kondisi</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td style="font-family: monospace; font-size: 12px; color: #fbbf24;">{{ $item->no_inventaris }}</td>
                                    <td class="item-name">{{ $item->nama_barang }}</td>
                                    <td>{{ $item->merk ?? '-' }}</td>
                                    <td>{{ $item->serial_number ?? '-' }}</td>
                                    <td>{{ $item->jumlah }}</td>
                                    <td>{{ $item->nama_pengadaan ?? '-' }}</td>
                                    <td>{{ $item->tahun_pengadaan ?? '-' }}</td>
                                    <td>
                                        <span class="status-badge {{ $item->kondisi_barang }}">
                                            {{ str_replace('_', ' ', $item->kondisi_barang) }}
                                        </span>
                                    </td>
                                    <td style="font-size: 12px; color: rgba(255,255,255,0.5); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->keterangan }}">
                                        {{ $item->keterangan ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <!-- ===== MODAL TAMBAH BARANG ===== -->
    <div class="modal-overlay" id="tambahModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Tambah Barang Baru</h2>
                <button class="modal-close" id="btn-close-modal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            
            <form action="{{ route('items.store') }}" method="POST" class="modal-form">
                @csrf
                
                <div class="form-group">
                    <label for="no_inventaris">Nomor Inventaris</label>
                    <input type="text" id="no_inventaris" name="no_inventaris" placeholder="Contoh: INV/2026/001" required value="{{ old('no_inventaris') }}">
                </div>

                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" id="nama_barang" name="nama_barang" placeholder="Nama barang" required value="{{ old('nama_barang') }}">
                </div>

                <div class="form-group">
                    <label for="merk">Merk</label>
                    <input type="text" id="merk" name="merk" placeholder="Merk / brand" value="{{ old('merk') }}">
                </div>

                <div class="form-group">
                    <label for="serial_number">Nomor Seri</label>
                    <input type="text" id="serial_number" name="serial_number" placeholder="Serial number" value="{{ old('serial_number') }}">
                </div>

                <div class="form-group">
                    <label for="jumlah">Jumlah</label>
                    <input type="number" id="jumlah" name="jumlah" min="1" placeholder="Jumlah barang" required value="{{ old('jumlah', 1) }}">
                </div>

                <div class="form-group">
                    <label for="nama_pengadaan">Nama Pengadaan</label>
                    <input type="text" id="nama_pengadaan" name="nama_pengadaan" placeholder="Nama pengadaan" value="{{ old('nama_pengadaan') }}">
                </div>

                <div class="form-group">
                    <label for="tahun_pengadaan">Tahun Pengadaan</label>
                    <input type="number" id="tahun_pengadaan" name="tahun_pengadaan" min="1900" max="{{ date('Y') + 1 }}" placeholder="YYYY" value="{{ old('tahun_pengadaan') }}">
                </div>

                <div class="form-group">
                    <label for="kondisi_barang">Kondisi</label>
                    <select id="kondisi_barang" name="kondisi_barang" required>
                        <option value="baik" {{ old('kondisi_barang') === 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ old('kondisi_barang') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ old('kondisi_barang') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                        <option value="hilang" {{ old('kondisi_barang') === 'hilang' ? 'selected' : '' }}>Hilang</option>
                    </select>
                </div>

                <div class="form-group form-field-full">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="btn-cancel-modal">Batal</button>
                    <button type="submit" class="btn-submit">Simpan Barang</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== SCRIPT UNTUK MODAL ===== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('tambahModal');
            const btnOpen = document.getElementById('btn-tambah-barang');
            const btnClose = document.getElementById('btn-close-modal');
            const btnCancel = document.getElementById('btn-cancel-modal');

            // Open Modal
            if (btnOpen) {
                btnOpen.addEventListener('click', function() {
                    modal.classList.add('active');
                });
            }

            // Close Modal
            const closeModal = function() {
                modal.classList.remove('active');
            };

            if (btnClose) btnClose.addEventListener('click', closeModal);
            if (btnCancel) btnCancel.addEventListener('click', closeModal);

            // Close modal when clicking outside container
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Keep modal open if there are validation errors
            @if ($errors->any())
                modal.classList.add('active');
            @endif
        });
    </script>
</body>
</html>