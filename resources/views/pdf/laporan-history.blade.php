<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan History Barang Keluar & Masuk - {{ $monthName }} {{ $year }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #333;
        }

        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative;
        }

        .logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 70px;
        }

        .kop-text h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .kop-text h1 {
            margin: 0 0 5px 0;
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text p {
            margin: 0;
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin: 25px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #333;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table-data th, .table-data td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        .table-data th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }

        .table-data td {
            font-size: 10px;
        }

        .table-data td.center {
            text-align: center;
        }

        .summary {
            margin-bottom: 15px;
            font-size: 12px;
        }

        .ttd-section {
            float: right;
            width: 250px;
            text-align: center;
            margin-top: 30px;
        }

        .ttd-section p {
            margin: 0;
        }

        .ttd-space {
            height: 80px;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        @if(!empty($logoBase64))
            <img src="{{ $logoBase64 }}" class="logo" alt="Logo Kemenhan">
        @endif
        <div class="kop-text">
            <h2>KEMENTERIAN PERTAHANAN REPUBLIK INDONESIA</h2>
            <h1>PUSAT DATA DAN INFORMASI</h1>
            <p>Jalan RS Fatmawati No. 1, Pondok Labu, Kecamatan Cilandak, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12450</p>
        </div>
    </div>

    <div class="title">
        LAPORAN HISTORY BARANG KELUAR & MASUK<br>
        PERIODE: {{ strtoupper($monthName) }} {{ $year }}
    </div>

    {{-- ===== SECTION: BARANG KELUAR ===== --}}
    <div class="section-title">A. Barang Keluar / Dipinjam</div>

    <div class="summary">
        Total transaksi keluar: <strong>{{ $outgoings->count() }}</strong>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="14%">Nama Peminjam</th>
                <th width="12%">No. Inventaris</th>
                <th width="16%">Nama Barang</th>
                <th width="8%">Jumlah</th>
                <th width="11%">Tgl Keluar</th>
                <th width="11%">Tgl Kembali</th>
                <th width="24%">Alasan Peminjaman</th>
            </tr>
        </thead>
        <tbody>
            @forelse($outgoings as $out)
            <tr>
                <td class="center">{{ $loop->iteration }}</td>
                <td>{{ $out->borrower->nama ?? '-' }}</td>
                <td>{{ $out->item->no_inventaris ?? '-' }}</td>
                <td><strong>{{ $out->item->nama_barang ?? '-' }}</strong></td>
                <td class="center">{{ $out->jumlah_keluar }} unit</td>
                <td class="center">{{ $out->tanggal_keluar ? $out->tanggal_keluar->format('d/m/Y') : '-' }}</td>
                <td class="center">{{ $out->tanggal_kembali ? $out->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                <td>{{ $out->keperluan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="center">Tidak ada data barang keluar pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===== SECTION: BARANG MASUK / DIKEMBALIKAN ===== --}}
    <div class="section-title">B. Barang Masuk / Dikembalikan</div>

    <div class="summary">
        Total transaksi masuk: <strong>{{ $returns->count() }}</strong>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="14%">Nama Peminjam</th>
                <th width="12%">No. Inventaris</th>
                <th width="16%">Nama Barang</th>
                <th width="8%">Jumlah</th>
                <th width="11%">Tgl Keluar</th>
                <th width="11%">Tgl Kembali</th>
                <th width="24%">Alasan Peminjaman</th>
            </tr>
        </thead>
        <tbody>
            @forelse($returns as $ret)
            <tr>
                <td class="center">{{ $loop->iteration }}</td>
                <td>{{ $ret->borrower->nama ?? '-' }}</td>
                <td>{{ $ret->item->no_inventaris ?? '-' }}</td>
                <td><strong>{{ $ret->item->nama_barang ?? '-' }}</strong></td>
                <td class="center">{{ $ret->jumlah_keluar }} unit</td>
                <td class="center">{{ $ret->tanggal_keluar ? $ret->tanggal_keluar->format('d/m/Y') : '-' }}</td>
                <td class="center">{{ $ret->tanggal_kembali ? $ret->tanggal_kembali->format('d/m/Y') : '-' }}</td>
                <td>{{ $ret->keperluan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="center">Tidak ada data barang dikembalikan pada periode ini.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-section">
        <p>Jakarta, {{ now()->translatedFormat('d F Y') }}</p>
        <p>Penanggung Jawab Inventaris,</p>
        <div class="ttd-space"></div>
        <p><strong>{{ auth()->user()->name ?? 'Admin Pusdatin' }}</strong></p>
    </div>

</body>
</html>
