<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Tagihan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 15mm;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header h3 {
            font-size: 16px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header p {
            font-size: 12px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 8px 6px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
        }

        tbody td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        /* Print styles */
        @media print {
            body {
                margin: 0;
                padding: 15mm;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

<div class="header">
    <h3>BUKTI TAGIHAN</h3>
    <p>Sistem Pengelolaan Sampah</p>
</div>

<table>
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="25%">Pelanggan</th>
            <th width="12%">Periode</th>
            <th width="15%">Tagihan</th>
            <th width="15%">Dibayar</th>
            <th width="15%">Sisa</th>
            <th width="13%">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tagihan as $i => $t)
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td>{{ $t->pelanggan->nama }}</td>
            <td class="center">{{ $t->periode }}</td>
            <td class="right">Rp {{ number_format($t->jml_tagihan_pokok, 0, ',', '.') }}</td>
            <td class="right">Rp {{ number_format($t->total_sudah_bayar, 0, ',', '.') }}</td>
            <td class="right">
                Rp {{ number_format($t->jml_tagihan_pokok - $t->total_sudah_bayar, 0, ',', '.') }}
            </td>
            <td class="center">{{ $t->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>