<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Barang - Inventaris Kemhan Pusdatin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/itemlist.css') }}">
    <style>
        /* Action buttons in table */
        .btn-action-group {
            display: flex;
            gap: 6px;
            align-items: center;
        }
        .btn-edit, .btn-delete-item {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.04);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .btn-edit svg, .btn-delete-item svg {
            width: 15px;
            height: 15px;
        }
        .btn-edit {
            color: #fbbf24;
        }
        .btn-edit:hover {
            background: rgba(251,191,36,0.12);
            border-color: rgba(251,191,36,0.25);
        }
        .btn-delete-item {
            color: #f87171;
        }
        .btn-delete-item:hover {
            background: rgba(248,113,113,0.12);
            border-color: rgba(248,113,113,0.25);
        }

        /* Alert error */
        .alert-error-msg {
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            padding: 14px 18px;
            border-radius: 10px;
            color: #f87171;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
            <div class="header">
                <div class="header-left">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 4px;">
                        <h1 style="margin-bottom: 0;">Halo, {{ auth()->user()->name }}!</h1>
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
                            $roleName = auth()->user()->role;
                            $label = $roleLabels[$roleName] ?? ucfirst($roleName);
                            $style = $roleColors[$roleName] ?? 'background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #ffffff;';
                        @endphp
                        <span style="font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; letter-spacing: 0.5px; {{ $style }}">
                            {{ $label }}
                        </span>
                    </div>
                    <p>Siap memonitoring inventaris?</p>
                </div>
                @if(auth()->user()->role !== 'kabid')
                <button class="btn-export" id="btn-tambah-barang">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Barang
                </button>
                @endif
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

            @if(session('error'))
                <div class="alert-error-msg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; flex-shrink: 0;">
                        <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <span>{{ session('error') }}</span>
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
                                    @if(auth()->user()->role !== 'kabid')
                                    <th></th>
                                    @endif
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
                                    @if(auth()->user()->role !== 'kabid')
                                    <td>
                                        <div class="btn-action-group">
                                            <button type="button" class="btn-edit" title="Edit Barang" onclick="openEditModal({{ json_encode($item) }})">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                                </svg>
                                            </button>
                                            <form action="{{ route('items.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus barang ini?')" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete-item" title="Hapus Barang">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <polyline points="3 6 5 6 21 6"/>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    @endif
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

    <!-- ===== MODAL EDIT BARANG ===== -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-container">
            <div class="modal-header">
                <h2 class="modal-title">Edit Barang</h2>
                <button class="modal-close" id="btn-close-edit-modal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
            
            <form id="editForm" method="POST" class="modal-form">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="edit_no_inventaris">Nomor Inventaris</label>
                    <input type="text" id="edit_no_inventaris" name="no_inventaris" required>
                </div>

                <div class="form-group">
                    <label for="edit_nama_barang">Nama Barang</label>
                    <input type="text" id="edit_nama_barang" name="nama_barang" required>
                </div>

                <div class="form-group">
                    <label for="edit_merk">Merk</label>
                    <input type="text" id="edit_merk" name="merk">
                </div>

                <div class="form-group">
                    <label for="edit_serial_number">Nomor Seri</label>
                    <input type="text" id="edit_serial_number" name="serial_number">
                </div>

                <div class="form-group">
                    <label for="edit_jumlah">Jumlah</label>
                    <input type="number" id="edit_jumlah" name="jumlah" min="0" required>
                </div>

                <div class="form-group">
                    <label for="edit_nama_pengadaan">Nama Pengadaan</label>
                    <input type="text" id="edit_nama_pengadaan" name="nama_pengadaan">
                </div>

                <div class="form-group">
                    <label for="edit_tahun_pengadaan">Tahun Pengadaan</label>
                    <input type="number" id="edit_tahun_pengadaan" name="tahun_pengadaan" min="1900" max="{{ date('Y') + 1 }}">
                </div>

                <div class="form-group">
                    <label for="edit_kondisi_barang">Kondisi</label>
                    <select id="edit_kondisi_barang" name="kondisi_barang" required>
                        <option value="baik">Baik</option>
                        <option value="rusak_ringan">Rusak Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                        <option value="hilang">Hilang</option>
                    </select>
                </div>

                <div class="form-group form-field-full">
                    <label for="edit_keterangan">Keterangan</label>
                    <textarea id="edit_keterangan" name="keterangan"></textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" id="btn-cancel-edit-modal">Batal</button>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== SCRIPTS ===== -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ===== TAMBAH MODAL =====
            const tambahModal = document.getElementById('tambahModal');
            const btnOpen = document.getElementById('btn-tambah-barang');
            const btnClose = document.getElementById('btn-close-modal');
            const btnCancel = document.getElementById('btn-cancel-modal');

            if (btnOpen) {
                btnOpen.addEventListener('click', function() {
                    tambahModal.classList.add('active');
                });
            }

            const closeTambahModal = function() {
                tambahModal.classList.remove('active');
            };

            if (btnClose) btnClose.addEventListener('click', closeTambahModal);
            if (btnCancel) btnCancel.addEventListener('click', closeTambahModal);

            tambahModal.addEventListener('click', function(e) {
                if (e.target === tambahModal) closeTambahModal();
            });

            // ===== EDIT MODAL =====
            const editModal = document.getElementById('editModal');
            const btnCloseEdit = document.getElementById('btn-close-edit-modal');
            const btnCancelEdit = document.getElementById('btn-cancel-edit-modal');

            const closeEditModal = function() {
                editModal.classList.remove('active');
            };

            if (btnCloseEdit) btnCloseEdit.addEventListener('click', closeEditModal);
            if (btnCancelEdit) btnCancelEdit.addEventListener('click', closeEditModal);

            editModal.addEventListener('click', function(e) {
                if (e.target === editModal) closeEditModal();
            });

            // Keep tambah modal open if there are validation errors (for store)
            @if ($errors->any() && !old('_method'))
                tambahModal.classList.add('active');
            @endif

            // Keep edit modal open if there are validation errors (for update)
            @if ($errors->any() && old('_method') === 'PUT')
                editModal.classList.add('active');
            @endif

            // ESC key to close modals
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeTambahModal();
                    closeEditModal();
                }
            });

            // Auto-dismiss success alerts
            document.querySelectorAll('.alert-success, .alert-error-msg').forEach(function(alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.3s ease';
                    setTimeout(function() { alert.remove(); }, 300);
                }, 5000);
            });
        });

        function openEditModal(item) {
            const editModal = document.getElementById('editModal');
            const editForm = document.getElementById('editForm');

            // Set form action URL
            editForm.action = '/daftar-barang/' + item.id;

            // Fill in fields
            document.getElementById('edit_no_inventaris').value = item.no_inventaris || '';
            document.getElementById('edit_nama_barang').value = item.nama_barang || '';
            document.getElementById('edit_merk').value = item.merk || '';
            document.getElementById('edit_serial_number').value = item.serial_number || '';
            document.getElementById('edit_jumlah').value = item.jumlah || 0;
            document.getElementById('edit_nama_pengadaan').value = item.nama_pengadaan || '';
            document.getElementById('edit_tahun_pengadaan').value = item.tahun_pengadaan || '';
            document.getElementById('edit_kondisi_barang').value = item.kondisi_barang || 'baik';
            document.getElementById('edit_keterangan').value = item.keterangan || '';

            editModal.classList.add('active');
        }
    </script>
</body>
</html>