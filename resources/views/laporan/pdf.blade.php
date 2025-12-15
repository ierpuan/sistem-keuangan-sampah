<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: DejaVu Sans; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background: #eee; }
        h2 { margin-top: 30px; }
    </style>
</head>
<body>

<h2>Laporan Keuangan Bulan {{ $bulan }}</h2>

<p>
    Total Pemasukan : Rp {{ number_format($totalPemasukan, 0, ',', '.') }} <br>
    Total Pengeluaran : Rp {{ number_format($totalPengeluaran, 0, ',', '.') }} <br>
    Saldo : Rp {{ number_format($saldo, 0, ',', '.') }}
</p>

<h3>Pemasukan</h3>
<table>
    <tr>
        <th>Tanggal</th>
        <th>Pelanggan</th>
        <th>Jumlah</th>
    </tr>
    @foreach ($daftarPemasukan as $item)
    <tr>
        <td>{{ $item->tgl_bayar->format('d/m/Y') }}</td>
        <td>{{ $item->tagihan->pelanggan->nama }}</td>
        <td align="right">Rp {{ number_format($item->jml_bayar_input, 0, ',', '.') }}</td>
    </tr>
    @endforeach
</table>

<h3>Pengeluaran</h3>
<table>
    <tr>
        <th>Tanggal</th>
        <th>Kategori</th>
        <th>Jumlah</th>
    </tr>
    @foreach ($daftarPengeluaran as $item)
    <tr>
        <td>{{ $item->tanggal_pengeluaran->format('d/m/Y') }}</td>
        <td>{{ $item->kategori }}</td>
        <td align="right">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
    </tr>
    @endforeach
</table>

</body>
</html>
