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

        .page {
            width: 210mm;
            height: 297mm;
            padding: 15mm 10mm;
            page-break-after: always;
            display: flex;
            flex-wrap: wrap;
            gap: 4mm;
            align-content: flex-start;
        }

        .container {
            width: calc(50% - 2mm);
            height: calc(20% - 2mm);
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
            font-size: 9px;
            line-height: 1.4;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 30%;
            font-weight: 500;
        }

        .nama-row {
            font-weight: bold;
            font-size: 10px;
        }

        .alamat-row {
            font-size: 8px;
            color: #555;
        }
        .tagihan-row {
            font-weight: bold;
            font-size: 10px;
            color: #000;
        }

        .amount-table {
            margin-top: 6px;
            border: 1px solid #333;
        }

        .amount-table td {
            padding: 4px 6px;
            border: 1px solid #333;
            font-size: 10px;
            font-weight: bold;
        }

        .right {
            text-align: right;
        }

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
        if ($count == 1 || ($count - 1) % 20 == 0) {
            if ($count > 1) echo '</div>';
            echo '<div class="page">';
        }
    ?>

    <div class="container">
        <div class="header">
            <h3>BUKTI TAGIHAN</h3>
            <p>Sistem Pengelolaan Sampah</p>
        </div>

        <table class="info-table">
            <tr>
                <td>Nama</td>
                <td class="nama-row">: {{ $t->pelanggan->nama }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td class="alamat-row">: {{ $t->pelanggan->alamat_lengkap }}</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>: {{ $t->periode }}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>: ....................</td>
            </tr>
            <tr>
                <td>Tagihan</td>
                <td class="tagihan-row">: Rp {{ number_format($t->jml_tagihan_pokok, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <?php
        if ($count % 20 == 0 || $loop->last) {
            echo '</div>';
        }
    ?>
@endforeach

</body>
</html>