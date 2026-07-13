<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persetujuan Barang Keluar - Inventaris</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/itemlist.css') }}">
    <style>
        .btn-action-group { display: flex; gap: 6px; align-items: center; }
        .btn-approve { padding: 6px 12px; background: rgba(34, 197, 94, 0.12); color: #4ade80; border: 1px solid rgba(34,197,94,0.3); border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-approve:hover { background: rgba(34, 197, 94, 0.2); }
        .btn-reject { padding: 6px 12px; background: rgba(239, 68, 68, 0.12); color: #f87171; border: 1px solid rgba(239,68,68,0.3); border-radius: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        .btn-reject:hover { background: rgba(239, 68, 68, 0.2); }
    </style>
</head>
<body>
    <div class="app-layout">
        @include('components.sidebar')

        <main class="main-content">
            <div class="header">
                <div class="header-left">
                    <h1>Approval Peminjaman</h1>
                    <p>Daftar permintaan peminjaman yang menunggu persetujuan Kasub.</p>
                </div>
            </div>

            @if(session('success'))
                <div class="alert-success" style="background: rgba(34, 197, 94, 0.12); border: 1px solid rgba(34, 197, 94, 0.25); padding: 14px 18px; border-radius: 10px; color: #4ade80; font-size: 13px; font-weight: 500; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error" style="background: rgba(239, 68, 68, 0.12); border: 1px solid rgba(239, 68, 68, 0.25); padding: 14px 18px; border-radius: 10px; color: #f87171; font-size: 13px; font-weight: 500; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <span class="card-title">Menunggu Persetujuan</span>
                    <span class="card-badge">{{ $outgoings->total() }} pending</span>
                </div>

                @if($outgoings->isEmpty())
                    <div class="empty-state" style="padding: 40px; text-align: center; color: rgba(255,255,255,0.4);">
                        <p>Tidak ada permintaan peminjaman yang menunggu persetujuan.</p>
                    </div>
                @else
                    <div class="scroll-area">
                        <table class="data-table" style="width: 100%; border-collapse: collapse; text-align: left;">
                            <thead>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.1);">
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Tanggal</th>
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Peminjam</th>
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Barang</th>
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Jml</th>
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Keperluan</th>
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Perekam</th>
                                    <th style="padding: 12px; font-size: 13px; color: rgba(255,255,255,0.5);">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($outgoings as $out)
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 12px; font-size: 14px;">{{ $out->tanggal_keluar->format('d/m/Y') }}</td>
                                    <td style="padding: 12px; font-size: 14px;">{{ $out->borrower->nama ?? '-' }}</td>
                                    <td style="padding: 12px; font-size: 14px;">{{ $out->item->nama_barang ?? '-' }}</td>
                                    <td style="padding: 12px; font-size: 14px;">{{ $out->jumlah_keluar }}</td>
                                    <td style="padding: 12px; font-size: 14px;">{{ $out->keperluan ?? '-' }}</td>
                                    <td style="padding: 12px; font-size: 14px;">{{ $out->recorder->name ?? '-' }}</td>
                                    <td style="padding: 12px; font-size: 14px;">
                                        <div class="btn-action-group">
                                            <form action="{{ route('approval.approve', $out->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-approve" onclick="return confirm('Apakah Anda yakin menyetujui peminjaman ini? Stok akan dikurangi.')">Setujui</button>
                                            </form>
                                            <form action="{{ route('approval.reject', $out->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-reject" onclick="return confirm('Tolak peminjaman ini?')">Tolak</button>
                                            </form>
                                        </div>
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
</body>
</html>
