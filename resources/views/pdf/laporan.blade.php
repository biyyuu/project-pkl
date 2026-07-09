<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Inventaris - {{ $monthName }} {{ $year }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
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
            width: 70px; /* Base64 encoded logo */
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
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        .table-data th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        .table-data td.center {
            text-align: center;
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
        LAPORAN REKAPITULASI BARANG KELUAR / PEMINJAMAN INVENTARIS<br>
        PERIODE: {{ strtoupper($monthName) }} {{ $year }}
    </div>

    <table class="table-data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="13%">Tanggal</th>
                <th width="15%">No. Inventaris</th>
                <th width="20%">Nama Barang / Merk</th>
                <th width="8%">Jumlah</th>
                <th width="18%">Peminjam</th>
                <th width="22%">Keperluan / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($outgoings as $index => $out)
            <tr>
                <td class="center">{{ $loop->iteration }}</td>
                <td class="center">{{ $out->tanggal_keluar->format('d/m/Y') }}</td>
                <td>{{ $out->item->no_inventaris ?? '-' }}<br><small>{{ $out->item->serial_number ?? '' }}</small></td>
                <td><strong>{{ $out->item->nama_barang ?? '-' }}</strong><br>{{ $out->item->merk ?? '-' }}</td>
                <td class="center">{{ $out->jumlah_keluar }} unit</td>
                <td>{{ $out->borrower->nama ?? '-' }}</td>
                <td>
                    <strong>{{ $out->keperluan ?? '-' }}</strong>
                    @if($out->keterangan)
                        <br><span style="font-size: 10px;">{{ $out->keterangan }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="center">Tidak ada catatan pengeluaran/peminjaman inventaris pada bulan ini.</td>
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
