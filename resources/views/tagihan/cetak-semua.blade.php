<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Tagihan - Bulk Print</title>
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
            padding: 0;
        }

        /* Layout 2 kolom untuk 1 halaman */
        .page {
            width: 210mm; /* A4 width */
            height: 297mm; /* A4 height */
            padding: 15mm 10mm; /* Padding top/bottom lebih besar */
            page-break-after: always;
            display: flex;
            flex-wrap: wrap;
            gap: 4mm;
            align-content: flex-start; /* Align ke atas secara konsisten */
        }

        /* Container untuk setiap bukti tagihan */
        .container {
            width: calc(50% - 2.5mm); /* 2 kolom dengan gap */
            height: calc(20% - 4mm); /* 10 baris per kolom */
            border: 1px solid #333;
            padding: 8px;
            page-break-inside: avoid;
            display: flex;
            flex-direction: column;
        }

        .header {
            text-align: center;
            margin-bottom: 6px;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
        }

        .header h3 {
            font-size: 10px;
            margin-bottom: 1px;
            font-weight: bold;
        }

        .header p {
            font-size: 8px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 2px 4px;
            font-size: 10px;
            line-height: 1.3;
        }

        .info-table td:first-child {
            width: 35%;
            font-weight: 500;
        }

        .amount-table {
            margin-top: 6px;
            border: 1px solid #333;
        }

        .amount-table td {
            padding: 3px 6px;
            border: 1px solid #333;
            font-size: 10px;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        /* Print styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .page {
                margin: 0;
                padding: 15mm 10mm;
            }

            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>
<body onload="window.print()">

<?php $count = 0; ?>

@foreach ($tagihan as $t)
    <?php
        $count++;
        // Mulai page baru setiap 20 item
        if ($count == 1 || ($count - 1) % 20 == 0) {
            if ($count > 1) echo '</div>'; // Tutup page sebelumnya
            echo '<div class="page">';
        }
    ?>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <h3>BUKTI TAGIHAN</h3>
            <p>Sistem Pengelolaan Sampah</p>
        </div>

        <!-- Info Pelanggan -->
        <table class="info-table">
            <tr>
                <td>Nama</td>
                <td>: {{ $t->pelanggan->nama }}</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>: {{ $t->periode}}</td>
            </tr>
            {{-- <tr>
                <td>Jatuh Tempo</td>
                <td>: {{ $t->jatuh_tempo->format('d-m-Y') }}</td>
            </tr> --}}
            {{-- <tr>
                <td>Status</td>
                <td>: <strong>{{ $t->status }}</strong></td>
            </tr> --}}
            <tr>
                <td>Keterangan</td>
                <td>: ....................</td>
        </table>

        <!-- Rincian Tagihan -->
        <table class="amount-table">
            <tr>
                <td>Tagihan</td>
                <td class="right">
                    Rp {{ number_format($t->jml_tagihan_pokok, 0, ',', '.') }}
                </td>
            </tr>
            {{-- <tr>
                <td>Terbayar</td>
                <td class="right">
                    Rp {{ number_format($t->total_sudah_bayar, 0, ',', '.') }}
                </td>
            </tr>
            <tr class="bold">
                <td>Sisa</td>
                <td class="right">
                    Rp {{ number_format($t->jml_tagihan_pokok - $t->total_sudah_bayar, 0, ',', '.') }}
                </td>
            </tr> --}}
        </table>

    </div>

    <?php
        // Tutup page jika sudah 20 item atau item terakhir
        if ($count % 20 == 0 || $loop->last) {
            echo '</div>';
        }
    ?>
@endforeach

</body>
</html>s