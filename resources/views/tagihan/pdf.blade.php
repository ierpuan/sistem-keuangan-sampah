<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Tagihan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #f0f0f0; }
        .right { text-align: right; }
    </style>
</head>
<body>

<div class="header">
    <h3>BUKTI TAGIHAN</h3>
    <p>Sistem Pengelolaan Keuangan</p>
</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Pelanggan</th>
            <th>Periode</th>
            <th>Tagihan</th>
            <th>Dibayar</th>
            <th>Sisa</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tagihan as $i => $t)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $t->pelanggan->nama }}</td>
            <td>{{ $t->periode }}</td>
            <td class="right">Rp {{ number_format($t->jml_tagihan_pokok,0,',','.') }}</td>
            <td class="right">Rp {{ number_format($t->total_sudah_bayar,0,',','.') }}</td>
            <td class="right">
                Rp {{ number_format($t->jml_tagihan_pokok - $t->total_sudah_bayar,0,',','.') }}
            </td>
            <td>{{ $t->status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
