<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Tagihan - Bulk Print</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        /* ===== PAGE A4 ===== */
        .page {
            width: 210mm;
            min-height: 297mm;
            display: flex;
            flex-wrap: wrap;
            gap: 5mm;
            page-break-after: always;
        }

        /* ===== BUKTI ===== */
        .container {
            width: calc(50% - 4mm);
            /* height: calc (16% - 4mm); */
            border: 1px solid #333;
            padding: 6px 8px;
            page-break-inside: avoid;
        }

        .header {
            text-align: center;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
            padding-bottom: 3px;
        }

        .header h3 {
            font-size: 10px;
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
            font-size: 9px;
            padding: 2px 4px;
            vertical-align: top;
            line-height: 1.4;
        }

        .info-table td:first-child {
            width: 30%;
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
        }

        /* ===== PRINT SETTING ===== */
        @page {
            size: A4;
            margin: 12mm 12mm;
        }

        @media print {
            body {
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
                <td>: {{ \Carbon\Carbon::parse($t->periode . '-01')->translatedFormat('F Y') }}</td>
            </tr>
            <tr>
                <td>Keterangan</td>
                <td>: ................................</td>
            </tr>
            <tr>
                <td>Tagihan</td>
                <td class="tagihan-row">
                    : Rp {{ number_format($t->jml_tagihan_pokok, 0, ',', '.') }}
                </td>
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
<script>
window.onafterprint = function () {
    window.close();
}
</script>

</html>
