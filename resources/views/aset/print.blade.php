<!DOCTYPE html>
<html>
<head>
    <title>Laporan Aset</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2, h3 {
            text-align: center;
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .summary {
            margin: 10px 0;
            font-size: 12px;
        }

        .summary span {
            margin-right: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
        }

        th, td {
            padding: 5px;
            text-align: center;
        }

        .left {
            text-align: left;
        }

        .ttd {
            margin-top: 40px;
            width: 100%;
        }

        .ttd-right {
            float: right;
            text-align: center;
        }

        button {
            margin-bottom: 10px;
        }

        @media print {
            button {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <h3>DINAS KOMINFO WONOGIRI</h3>
    <h2>LAPORAN DATA ASET</h2>
    <p>Tanggal Cetak: {{ date('d-m-Y H:i') }}</p>
</div>

<button onclick="window.print()">🖨 Print / Save PDF</button>

<div class="summary">
    <span><strong>Total Aset:</strong> {{ $totalAset }}</span>
    <span><strong>Total Nilai:</strong> Rp {{ number_format($totalNilai, 0, ',', '.') }}</span>
    <span><strong>Baik:</strong> {{ $kondisi['Baik'] }}</span>
    <span><strong>Rusak:</strong> {{ $kondisi['Rusak'] }}</span>
    <span><strong>Perbaikan:</strong> {{ $kondisi['Perbaikan'] }}</span>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kode</th>
            <th>Merk</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Kecamatan</th>
            <th>Desa</th>
            <th>Kondisi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($asets as $i => $aset)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td class="left">{{ $aset->nama_barang }}</td>
            <td>{{ $aset->kode_barang }}</td>
            <td>{{ $aset->merk_tipe }}</td>
            <td>{{ $aset->jumlah }}</td>
            <td>Rp {{ number_format($aset->harga, 0, ',', '.') }}</td>
            <td>{{ $aset->kecamatan }}</td>
            <td>{{ $aset->desa }}</td>
            <td>{{ $aset->kondisi }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="ttd">
    <div class="ttd-right">
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>(_____________________)</strong></p>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>

</body>
</html>