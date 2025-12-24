<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 3px 0;
            font-size: 16px;
        }
        .header h3 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 12px;
        }
        /* .summary {
            margin-bottom: 20px;
            padding: 10px;
            background: #f5f5f5;
            border: 1px solid #ddd;
        }
        .summary table {
            width: 100%;
            border: none;
        }
        .summary td {
            border: none;
            padding: 3px 5px;
        }
        .summary .label {
            font-weight: bold;
            width: 40%;
        }
        .summary .value {
            text-align: right;
        } */
        table.main {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.main th, table.main td {
            border: 1px solid #333;
            padding: 6px 8px;
            font-size: 10px;
        }
        table.main th {
            background: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }
        table.main td.number {
            text-align: right;
        }
        table.main td.center {
            text-align: center;
        }
        table.main tr.saldo-row {
            background: #f9f9f9;
            font-weight: bold;
        }
        table.main tr.total-row {
            background: #e8e8e8;
            font-weight: bold;
        }
        table.main tr.grand-total {
            background: #d0d0d0;
            font-weight: bold;
        }
        table.main tr.pemasukan-row {
            background: white;
            font-weight: normal;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>LAPORAN KEUANGAN</h2>
    <h3>Pengelolaan Sampah Desa Sambopinggir</h3>
    <p>Periode: {{ \Carbon\Carbon::parse($bulan)->translatedFormat('F Y') }}</p>
</div>

<!-- Summary Kumulatif -->
{{-- <div class="summary">
    <table>
        <tr>
            <td class="label">Saldo Kumulatif Tahun Lalu</td>
            <td class="value">Rp {{ number_format($saldoKumulatifTahunLalu, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Saldo Kumulatif Bulan Lalu</td>
            <td class="value">Rp {{ number_format($saldoKumulatifBulanLalu, 0, ',', '.') }}</td>
        </tr>
        <tr style="border-top: 2px solid #999;">
            <td class="label">Total Pemasukan Bulan Ini</td>
            <td class="value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Total Pengeluaran Bulan Ini</td>
            <td class="value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Saldo Bulan Ini</td>
            <td class="value">Rp {{ number_format($saldo, 0, ',', '.') }}</td>
        </tr>
        <tr style="border-top: 2px solid #999;">
            <td class="label">Saldo Kumulatif Sampai Bulan Ini</td>
            <td class="value"><strong>Rp {{ number_format($saldoKumulatifSampaiSekarang, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td class="label">Saldo Kumulatif Tahun Ini</td>
            <td class="value"><strong>Rp {{ number_format($saldoKumulatifTahunIni, 0, ',', '.') }}</strong></td>
        </tr>
    </table>
</div> --}}

<!-- Tabel Transaksi -->
<table class="main">
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th style="width: 12%;">Tanggal</th>
            <th style="width: 35%;">Uraian</th>
            <th style="width: 16%;">Masuk (Rp)</th>
            <th style="width: 16%;">Keluar (Rp)</th>
            <th style="width: 16%;">Saldo (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <!-- saldo tahun lalu -->
        <tr class="grand-total">
            <td colspan="3" class="center"><strong>(Kumulatif Akhir Tahun Lalu)</strong></td>
            <td class="number">-</td>
            <td class="number">-</td>
            <td class="number">{{ number_format($saldoKumulatifTahunLalu, 0, ',', '.') }}</td>
        </tr>
        <!-- Saldo Awal -->
        <tr class="grand-total">
            <td colspan="3" class="center"><strong>(Kumulatif Bulan Lalu)</strong></td>
            <td class="number">-</td>
            <td class="number">-</td>
            <td class="number">{{ number_format($saldoKumulatifBulanLalu, 0, ',', '.') }}</td>
        </tr>

        @php
            $no = 1;
        @endphp

        @foreach ($transaksiGabungan as $item)
        <tr class="{{ $item['masuk'] > 0 ? 'pemasukan-row' : '' }}">
            <td class="center">{{ $no++ }}</td>
            <td class="center">{{ $item['tanggal']->format('d/m/Y') }}</td>
            <td>{{ $item['uraian'] }}</td>
            <td class="number">{{ $item['masuk'] > 0 ? number_format($item['masuk'], 0, ',', '.') : '-' }}</td>
            <td class="number">{{ $item['keluar'] > 0 ? number_format($item['keluar'], 0, ',', '.') : '-' }}</td>
            <td class="number">{{ number_format($item['saldo'], 0, ',', '.') }}</td>
        </tr>
        @endforeach

        <!-- Total Bulan Ini -->
        <tr class="total-row">
            <td colspan="3" class="center"><strong>Jumlah Bulan Ini</strong></td>
            <td class="number">{{ number_format($totalPemasukan, 0, ',', '.') }}</td>
            <td class="number">{{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
            <td class="number">{{ number_format($saldo, 0, ',', '.') }}</td>
        </tr>

        <!-- Saldo Akhir -->
        <tr class="grand-total">
            <td colspan="3" class="center"><strong>(Kumulatif Sampai Bulan Ini)</strong></td>
            <td class="number">-</td>
            <td class="number">-</td>
            <td class="number">{{ number_format($saldoKumulatifSampaiSekarang, 0, ',', '.') }}</td>
        </tr>
        <!-- Saldo Kumulatif Tahun Ini -->
        <tr class="grand-total">
            <td colspan="3" class="center"><strong>(Saldo Kumulatif Tahun Ini)</strong></td>
            <td class="number">-</td>
            <td class="number">-</td>
            <td class="number">{{ number_format($saldoKumulatifTahunIni, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<div style="margin-top:40px; width:100%;">

    <!-- Tanggal kanan atas -->
    <div style="text-align:right; margin-bottom:20px;">
        {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
    </div>

    <!-- Tanda tangan -->
    <table style="width:100%; border:none; margin-top:5px;">
        <tr>
            <td style="width:50%; border:none; text-align:center; padding-top:20px;">
                <p style="margin-bottom:60px;">Pemerintah Desa Sambopinggir</p>
                <p style="margin:0;">____________________</p>
                <p style="margin-top:8px;">Aminah</p>
            </td>

            <td style="width:50%; border:none; text-align:center; padding-top:20px;">
                <p style="margin-bottom:60px;">Penanggung Jawab Pengelolaan Sampah</p>
                <p style="margin:0;">____________________</p>
                <p style="margin-top:8px;">Ahmad Syaifuddin</p>
            </td>
        </tr>
    </table>

</div>



</body>
</html>