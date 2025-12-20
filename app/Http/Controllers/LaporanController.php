<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiPembayaran;
use App\Models\Pengeluaran;


// Library PDF & Excel
use Barryvdh\DomPDF\Facade\Pdf;
// use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Concerns\FromArray;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Carbon\Carbon;



class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->input('bulan', Carbon::now()->format('Y-m'));
        [$tahun, $bulanAngka] = explode('-', $bulan);

        $totalPemasukan = TransaksiPembayaran::whereYear('tgl_bayar', $tahun)
            ->whereMonth('tgl_bayar', $bulanAngka)
            ->sum('jml_bayar_input');

        $totalPengeluaran = Pengeluaran::whereYear('tanggal_pengeluaran', $tahun)
            ->whereMonth('tanggal_pengeluaran', $bulanAngka)
            ->sum('jumlah');

        $daftarPemasukan = TransaksiPembayaran::with(['tagihan.pelanggan', 'pengguna'])
            ->whereYear('tgl_bayar', $tahun)
            ->whereMonth('tgl_bayar', $bulanAngka)
            ->orderBy('tgl_bayar', 'desc')
            ->get();

        $daftarPengeluaran = Pengeluaran::with('pengguna')
            ->whereYear('tanggal_pengeluaran', $tahun)
            ->whereMonth('tanggal_pengeluaran', $bulanAngka)
            ->orderBy('tanggal_pengeluaran', 'desc')
            ->get();

        $saldo = $totalPemasukan - $totalPengeluaran;

        $pengeluaran_kategori = Pengeluaran::selectRaw('kategori, SUM(jumlah) as total')
            ->whereYear('tanggal_pengeluaran', $tahun)
            ->whereMonth('tanggal_pengeluaran', $bulanAngka)
            ->groupBy('kategori')
            ->get();

        return view('laporan.index', compact(
            'bulan',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'daftarPemasukan',
            'daftarPengeluaran',
            'pengeluaran_kategori',
            'totalPengeluaran'
        ));
    }

    // ================= PDF =================
    public function exportPdf(Request $request)
{
    $bulan = $request->get('bulan', now()->format('Y-m'));

    // Parse bulan dan tahun
    $periode = \Carbon\Carbon::parse($bulan);
    $tahunSekarang = $periode->year;
    $bulanSekarang = $periode->month;

    // Ambil data pemasukan dan pengeluaran bulan ini
    $daftarPemasukan = TransaksiPembayaran::whereYear('tgl_bayar', $tahunSekarang)
        ->whereMonth('tgl_bayar', $bulanSekarang)
        ->with('tagihan.pelanggan')
        ->orderBy('tgl_bayar')
        ->get();

    $daftarPengeluaran = Pengeluaran::whereYear('tanggal_pengeluaran', $tahunSekarang)
        ->whereMonth('tanggal_pengeluaran', $bulanSekarang)
        ->orderBy('tanggal_pengeluaran')
        ->get();

    // Hitung total bulan ini
    $totalPemasukan = $daftarPemasukan->sum('jml_bayar_input');
    $totalPengeluaran = $daftarPengeluaran->sum('jumlah');
    $saldo = $totalPemasukan - $totalPengeluaran;

    // Hitung saldo kumulatif tahun lalu (sampai 31 Desember tahun lalu)
    $tahunLalu = $tahunSekarang - 1;
    $pemasukanTahunLalu = TransaksiPembayaran::whereYear('tgl_bayar', '<=', $tahunLalu)
        ->sum('jml_bayar_input');
    $pengeluaranTahunLalu = Pengeluaran::whereYear('tanggal_pengeluaran', '<=', $tahunLalu)
        ->sum('jumlah');
    $saldoKumulatifTahunLalu = $pemasukanTahunLalu - $pengeluaranTahunLalu;

    // Hitung saldo kumulatif bulan lalu
    $bulanLalu = $periode->copy()->subMonth();
    $pemasukanSampaiBulanLalu = TransaksiPembayaran::where('tgl_bayar', '<', $periode->startOfMonth())
        ->sum('jml_bayar_input');
    $pengeluaranSampaiBulanLalu = Pengeluaran::where('tanggal_pengeluaran', '<', $periode->startOfMonth())
        ->sum('jumlah');
    $saldoKumulatifBulanLalu = $pemasukanSampaiBulanLalu - $pengeluaranSampaiBulanLalu;

    // Hitung saldo kumulatif sampai sekarang
    $saldoKumulatifSampaiSekarang = $saldoKumulatifBulanLalu + $saldo;

    // Hitung saldo kumulatif tahun ini (Januari s/d bulan sekarang)
    $pemasukanTahunIni = TransaksiPembayaran::whereYear('tgl_bayar', $tahunSekarang)
        ->where('tgl_bayar', '<=', $periode->endOfMonth())
        ->sum('jml_bayar_input');
    $pengeluaranTahunIni = Pengeluaran::whereYear('tanggal_pengeluaran', $tahunSekarang)
        ->where('tanggal_pengeluaran', '<=', $periode->endOfMonth())
        ->sum('jumlah');
    $saldoKumulatifTahunIni = $pemasukanTahunIni - $pengeluaranTahunIni;

    // === PERUBAHAN: Gabungkan transaksi dengan pemasukan sebagai SATU BARIS TOTAL ===
    $transaksiGabungan = collect();
    $runningBalance = $saldoKumulatifBulanLalu;

    // Tambahkan SATU baris untuk total pemasukan bulan ini
    if ($totalPemasukan > 0) {
        $runningBalance += $totalPemasukan;
        $transaksiGabungan->push([
            'tanggal' => $periode->startOfMonth(), // Gunakan tanggal awal bulan
            'uraian' => 'Total Pemasukan Bulan Ini',
            'masuk' => $totalPemasukan,
            'keluar' => 0,
            'saldo' => $runningBalance,
        ]);
    }

    // Tambahkan setiap pengeluaran
    foreach ($daftarPengeluaran as $pengeluaran) {
        $runningBalance -= $pengeluaran->jumlah;
        $transaksiGabungan->push([
            'tanggal' => $pengeluaran->tanggal_pengeluaran,
            'uraian' => $pengeluaran->kategori . ' - ' . $pengeluaran->keterangan,
            'masuk' => 0,
            'keluar' => $pengeluaran->jumlah,
            'saldo' => $runningBalance,
        ]);
    }

    // Urutkan berdasarkan tanggal
    $transaksiGabungan = $transaksiGabungan->sortBy('tanggal')->values();

    $pdf = PDF::loadView('laporan.pdf', compact(
        'bulan',
        'totalPemasukan',
        'totalPengeluaran',
        'saldo',
        'saldoKumulatifTahunLalu',
        'saldoKumulatifBulanLalu',
        'saldoKumulatifSampaiSekarang',
        'saldoKumulatifTahunIni',
        'transaksiGabungan'
    ));

    return $pdf->download('laporan-keuangan-' . $bulan . '.pdf');
}

    // ================= Excel tanpa Export Class =================

    public function exportExcel(Request $request)
{
    $bulan = $request->input('bulan', now()->format('Y-m'));
    [$tahun, $bulanAngka] = explode('-', $bulan);

    // Parse bulan dan tahun
    $periode = \Carbon\Carbon::parse($bulan);
    $tahunSekarang = $periode->year;
    $bulanSekarang = $periode->month;

    // Ambil data pemasukan dan pengeluaran bulan ini
    $daftarPemasukan = TransaksiPembayaran::whereYear('tgl_bayar', $tahunSekarang)
        ->whereMonth('tgl_bayar', $bulanSekarang)
        ->with('tagihan.pelanggan')
        ->orderBy('tgl_bayar')
        ->get();

    $daftarPengeluaran = Pengeluaran::whereYear('tanggal_pengeluaran', $tahunSekarang)
        ->whereMonth('tanggal_pengeluaran', $bulanSekarang)
        ->orderBy('tanggal_pengeluaran')
        ->get();

    // Hitung total bulan ini
    $totalPemasukan = $daftarPemasukan->sum('jml_bayar_input');
    $totalPengeluaran = $daftarPengeluaran->sum('jumlah');
    $saldo = $totalPemasukan - $totalPengeluaran;

    // Hitung saldo kumulatif tahun lalu
    $tahunLalu = $tahunSekarang - 1;
    $pemasukanTahunLalu = TransaksiPembayaran::whereYear('tgl_bayar', '<=', $tahunLalu)
        ->sum('jml_bayar_input');
    $pengeluaranTahunLalu = Pengeluaran::whereYear('tanggal_pengeluaran', '<=', $tahunLalu)
        ->sum('jumlah');
    $saldoKumulatifTahunLalu = $pemasukanTahunLalu - $pengeluaranTahunLalu;

    // Hitung saldo kumulatif bulan lalu
    $bulanLalu = $periode->copy()->subMonth();
    $pemasukanSampaiBulanLalu = TransaksiPembayaran::where('tgl_bayar', '<', $periode->startOfMonth())
        ->sum('jml_bayar_input');
    $pengeluaranSampaiBulanLalu = Pengeluaran::where('tanggal_pengeluaran', '<', $periode->startOfMonth())
        ->sum('jumlah');
    $saldoKumulatifBulanLalu = $pemasukanSampaiBulanLalu - $pengeluaranSampaiBulanLalu;

    // Hitung saldo kumulatif sampai sekarang
    $saldoKumulatifSampaiSekarang = $saldoKumulatifBulanLalu + $saldo;

    // Hitung saldo kumulatif tahun ini
    $pemasukanTahunIni = TransaksiPembayaran::whereYear('tgl_bayar', $tahunSekarang)
        ->where('tgl_bayar', '<=', $periode->endOfMonth())
        ->sum('jml_bayar_input');
    $pengeluaranTahunIni = Pengeluaran::whereYear('tanggal_pengeluaran', $tahunSekarang)
        ->where('tanggal_pengeluaran', '<=', $periode->endOfMonth())
        ->sum('jumlah');
    $saldoKumulatifTahunIni = $pemasukanTahunIni - $pengeluaranTahunIni;

    // Buat transaksi gabungan (sama seperti PDF)
    $transaksiGabungan = collect();
    $runningBalance = $saldoKumulatifBulanLalu;

    // Tambahkan SATU baris untuk total pemasukan
    if ($totalPemasukan > 0) {
        $runningBalance += $totalPemasukan;
        $transaksiGabungan->push([
            'tanggal' => $periode->startOfMonth(),
            'uraian' => 'Total Pemasukan Bulan Ini',
            'masuk' => $totalPemasukan,
            'keluar' => 0,
            'saldo' => $runningBalance,
        ]);
    }

    // Tambahkan setiap pengeluaran
    foreach ($daftarPengeluaran as $pengeluaran) {
        $runningBalance -= $pengeluaran->jumlah;
        $transaksiGabungan->push([
            'tanggal' => $pengeluaran->tanggal_pengeluaran,
            'uraian' => $pengeluaran->kategori . ' - ' . $pengeluaran->keterangan,
            'masuk' => 0,
            'keluar' => $pengeluaran->jumlah,
            'saldo' => $runningBalance,
        ]);
    }

    // Urutkan berdasarkan tanggal
    $transaksiGabungan = $transaksiGabungan->sortBy('tanggal')->values();

    // ============= BUAT EXCEL =============
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // ============= SET PAGE LAYOUT F4 (FOLIO) =============
    $sheet->getPageSetup()
        ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_FOLIO) // F4 = Folio (8.5" x 13")
        ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT)
        ->setFitToWidth(1)
        ->setFitToHeight(0);

    // Set margin
    $sheet->getPageMargins()
        ->setTop(0.5)
        ->setRight(0.5)
        ->setLeft(0.5)
        ->setBottom(0.5)
        ->setHeader(0.3)
        ->setFooter(0.3);

    /* ================= HEADER ================= */
    $sheet->setCellValue('A1', 'LAPORAN KEUANGAN');
    $sheet->mergeCells('A1:F1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A2', 'Pengelolaan Sampah Desa Sambopinggir');
    $sheet->mergeCells('A2:F2');
    $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A3', 'Periode: ' . Carbon::parse($bulan)->translatedFormat('F Y'));
    $sheet->mergeCells('A3:F3');
    $sheet->getStyle('A3')->getFont()->setSize(12);
    $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    /* ================= TABEL TRANSAKSI ================= */
    $startRow = 5;

    // Header tabel
    $sheet->fromArray(['No', 'Tanggal', 'Uraian', 'Masuk (Rp)', 'Keluar (Rp)', 'Saldo (Rp)'], null, 'A' . $startRow);
    $sheet->getStyle("A{$startRow}:F{$startRow}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E0E0E0']
        ],
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
        ],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
    ]);

    $row = $startRow + 1;

    // Saldo Tahun Lalu
    $sheet->setCellValue('A' . $row, '(Saldo Kumulatif Tahun Lalu)');
    $sheet->mergeCells('A' . $row . ':C' . $row);
    $sheet->setCellValue('D' . $row, '-');
    $sheet->setCellValue('E' . $row, '-');
    $sheet->setCellValue('F' . $row, $saldoKumulatifTahunLalu);
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'D0D0D0']
        ],
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
        ]
    ]);
    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("E{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    $row++;

    // Saldo Bulan Lalu
    $sheet->setCellValue('A' . $row, '(Saldo Kumulatif Bulan Lalu)');
    $sheet->mergeCells('A' . $row . ':C' . $row);
    $sheet->setCellValue('D' . $row, '-');
    $sheet->setCellValue('E' . $row, '-');
    $sheet->setCellValue('F' . $row, $saldoKumulatifBulanLalu);
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'D0D0D0']
        ],
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
        ]
    ]);
    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("E{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    $row++;

    // Transaksi gabungan
    $no = 1;
    foreach ($transaksiGabungan as $item) {
        $sheet->setCellValue('A' . $row, $no++);
        $sheet->setCellValue('B' . $row, Carbon::parse($item['tanggal'])->format('d/m/Y'));
        $sheet->setCellValue('C' . $row, $item['uraian']);
        $sheet->setCellValue('D' . $row, $item['masuk'] > 0 ? $item['masuk'] : '-');
        $sheet->setCellValue('E' . $row, $item['keluar'] > 0 ? $item['keluar'] : '-');
        $sheet->setCellValue('F' . $row, $item['saldo']);

        // Style untuk baris pemasukan (background putih)
        if ($item['masuk'] > 0) {
            $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFFFF']
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN]
                ]
            ]);
        } else {
            $sheet->getStyle("A{$row}:F{$row}")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        $sheet->getStyle("A{$row}:B{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $row++;
    }

    // Total Bulan Ini
    $sheet->setCellValue('A' . $row, 'Jumlah Bulan Ini');
    $sheet->mergeCells('A' . $row . ':C' . $row);
    $sheet->setCellValue('D' . $row, $totalPemasukan);
    $sheet->setCellValue('E' . $row, $totalPengeluaran);
    $sheet->setCellValue('F' . $row, $saldo);
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E8E8E8']
        ],
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
        ]
    ]);
    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("D{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    $row++;

    // Saldo Kumulatif Sampai Bulan Ini
    $sheet->setCellValue('A' . $row, '(Kumulatif Sampai Bulan Ini)');
    $sheet->mergeCells('A' . $row . ':C' . $row);
    $sheet->setCellValue('D' . $row, '-');
    $sheet->setCellValue('E' . $row, '-');
    $sheet->setCellValue('F' . $row, $saldoKumulatifSampaiSekarang);
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'D0D0D0']
        ],
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
        ]
    ]);
    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("D{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
    $row++;

    // Saldo Kumulatif Tahun Ini
    $sheet->setCellValue('A' . $row, '(Saldo Kumulatif Tahun Ini)');
    $sheet->mergeCells('A' . $row . ':C' . $row);
    $sheet->setCellValue('D' . $row, '-');
    $sheet->setCellValue('E' . $row, '-');
    $sheet->setCellValue('F' . $row, $saldoKumulatifTahunIni);
    $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'D0D0D0']
        ],
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
        ]
    ]);
    $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle("D{$row}:F{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    /* ================= TANGGAL DI POJOK KANAN ================= */
    $sheet->setCellValue('A15', Carbon::now()->translatedFormat('d F Y'));
    $sheet->mergeCells('A15:F15');
    $sheet->getStyle('A15')->getFont()->setSize(12);
    $sheet->getStyle('A15')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

    $sheet->setCellValue('A17', 'Mengetahui,');
    $sheet->mergeCells('A17:F17');
    $sheet->getStyle('A17')->getFont()->setSize(12);
    $sheet->getStyle('A17')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A21', '____________________');
    $sheet->mergeCells('A21:C21');
    $sheet->setCellValue('D21', '____________________');
    $sheet->mergeCells('D21:F21');
    $sheet->getStyle('A21')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D21')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A22', 'Ketua');
    $sheet->mergeCells('A22:C22');
    $sheet->setCellValue('D22', 'Bendahara');
    $sheet->mergeCells('D22:F22');
    $sheet->getStyle('A22')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('D22')->getFont()->setBold(true)->setSize(12);
    $sheet->getStyle('A22')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $sheet->getStyle('D22')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


    /* ================= UKURAN KOLOM ================= */
    $sheet->getColumnDimension('A')->setWidth(8);
    $sheet->getColumnDimension('B')->setWidth(20);
    $sheet->getColumnDimension('C')->setWidth(35);
    $sheet->getColumnDimension('D')->setWidth(20);
    $sheet->getColumnDimension('E')->setWidth(18);
    $sheet->getColumnDimension('F')->setWidth(22);

    $filename = 'laporan_keuangan_' . $bulan . '.xlsx';
    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]);
}

}
