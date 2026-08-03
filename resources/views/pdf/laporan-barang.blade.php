<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Daftar Barang Inventaris - {{ $monthName }} {{ $year }}</title>
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

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
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
            margin-bottom: 20px;
            font-size: 12px;
        }

        .summary strong {
            font-weight: bold;
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
        LAPORAN DAFTAR BARANG INVENTARIS<br>
        PERIODE: {{ strtoupper($monthName) }} {{ $year }}
    </div>

    <div class="summary">
        Total Barang: <strong>{{ $items->count() }}</strong> &nbsp;&bull;&nbsp;
        Total Stok: <strong>{{ $items->sum('jumlah') }} unit</strong> &nbsp;&bull;&nbsp;
        Kondisi Baik: <strong>{{ $items->where('kondisi_barang', 'baik')->count() }}</strong> &nbsp;&bull;&nbsp;
        Rusak Ringan: <strong>{{ $items->where('kondisi_barang', 'rusak_ringan')->count() }}</strong> &nbsp;&bull;&nbsp;
        Rusak Berat: <strong>{{ $items->where('kondisi_barang', 'rusak_berat')->count() }}</strong>
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="12%">No. Inventaris</th>
                <th width="16%">Nama Barang</th>
                <th width="10%">Merk</th>
                <th width="12%">No. Seri</th>
                <th width="6%">Jumlah</th>
                <th width="12%">Pengadaan</th>
                <th width="6%">Tahun</th>
                <th width="8%">Kondisi</th>
                <th width="14%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
            <tr>
                <td class="center">{{ $loop->iteration }}</td>
                <td>{{ $item->no_inventaris }}</td>
                <td><strong>{{ $item->nama_barang }}</strong></td>
                <td>{{ $item->merk ?? '-' }}</td>
                <td>{{ $item->serial_number ?? '-' }}</td>
                <td class="center">{{ $item->jumlah }}</td>
                <td>{{ $item->nama_pengadaan ?? '-' }}</td>
                <td class="center">{{ $item->tahun_pengadaan ?? '-' }}</td>
                <td class="center">{{ str_replace('_', ' ', ucfirst($item->kondisi_barang)) }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="center">Tidak ada data barang inventaris.</td>
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
