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

        $pengeluaran_kategori = Pengeluaran::selectRaw('kategori, SUM(jumlah) as total')
            ->whereYear('tanggal_pengeluaran', $tahun)
            ->whereMonth('tanggal_pengeluaran', $bulanAngka)
            ->groupBy('kategori')
            ->get();

        $saldo = $totalPemasukan - $totalPengeluaran;

        // Render langsung dari view Blade (misal gunakan index.blade.php atau buat pdf.blade.php)
        $pdf = Pdf::loadView('laporan.pdf', compact(
            'bulan',
            'totalPemasukan',
            'totalPengeluaran',
            'saldo',
            'daftarPemasukan',
            'daftarPengeluaran',
            'pengeluaran_kategori'
        ));

        return $pdf->download('laporan_keuangan_'.$bulan.'.pdf');
    }

    // ================= Excel tanpa Export Class =================

    public function exportExcel(Request $request)
{
    $bulan = $request->input('bulan', now()->format('Y-m'));
    [$tahun, $bulanAngka] = explode('-', $bulan);

    $totalPemasukan = TransaksiPembayaran::whereYear('tgl_bayar', $tahun)
        ->whereMonth('tgl_bayar', $bulanAngka)
        ->sum('jml_bayar_input');

    $totalPengeluaran = Pengeluaran::whereYear('tanggal_pengeluaran', $tahun)
        ->whereMonth('tanggal_pengeluaran', $bulanAngka)
        ->sum('jumlah');

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    /* ================= JUDUL ================= */
    $sheet->setCellValue('B1', 'LAPORAN KEUANGAN BULAN ' . strtoupper(Carbon::parse($bulan)->translatedFormat('F Y')));
    $sheet->mergeCells('B1:D1');
    $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    /* ================= RINGKASAN ================= */
    $sheet->setCellValue('B3', 'Total Pemasukan');
    $sheet->setCellValue('C3', $totalPemasukan);

    $sheet->setCellValue('B4', 'Total Pengeluaran');
    $sheet->setCellValue('C4', $totalPengeluaran);

    $sheet->setCellValue('B5', 'Saldo');
    $sheet->setCellValue('C5', $totalPemasukan - $totalPengeluaran);

    $sheet->getStyle('B3:B5')->getFont()->setBold(true);

    /* ================= PEMASUKAN ================= */
    $startRow = 7;
    $sheet->setCellValue('B' . $startRow, 'PEMASUKAN');
    $sheet->mergeCells('B' . $startRow . ':D' . $startRow);
    $sheet->getStyle('B' . $startRow)->getFont()->setBold(true);

    $headerRow = $startRow + 1;
    $sheet->fromArray(['Tanggal', 'Pelanggan', 'Jumlah'], null, 'B' . $headerRow);

    $sheet->getStyle("B{$headerRow}:D{$headerRow}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E8E8E8']
        ],
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
        ]
    ]);

    $row = $headerRow + 1;

    $pemasukan = TransaksiPembayaran::with('tagihan.pelanggan')
        ->whereYear('tgl_bayar', $tahun)
        ->whereMonth('tgl_bayar', $bulanAngka)
        ->get();

    foreach ($pemasukan as $item) {
        $sheet->setCellValue('B' . $row, Carbon::parse($item->tgl_bayar)->format('d/m/Y'));
        $sheet->setCellValue('C' . $row, $item->tagihan->pelanggan->nama ?? '-');
        $sheet->setCellValue('D' . $row, $item->jml_bayar_input);
        $row++;
    }

    $sheet->getStyle("B{$headerRow}:D" . ($row - 1))
        ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    /* ================= PENGELUARAN ================= */
    $row += 2;
    $sheet->setCellValue('B' . $row, 'PENGELUARAN');
    $sheet->mergeCells('B' . $row . ':D' . $row);
    $sheet->getStyle('B' . $row)->getFont()->setBold(true);

    $row++;
    $sheet->fromArray(['Tanggal', 'Kategori', 'Jumlah'], null, 'B' . $row);

    $sheet->getStyle("B{$row}:D{$row}")->applyFromArray([
        'font' => ['bold' => true],
        'fill' => [
            'fillType' => Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'E8E8E8']
        ],
        'borders' => [
            'allBorders' => ['borderStyle' => Border::BORDER_THIN]
        ]
    ]);

    $row++;

    $pengeluaran = Pengeluaran::whereYear('tanggal_pengeluaran', $tahun)
        ->whereMonth('tanggal_pengeluaran', $bulanAngka)
        ->get();

    foreach ($pengeluaran as $item) {
        $sheet->setCellValue('B' . $row, Carbon::parse($item->tanggal_pengeluaran)->format('d/m/Y'));
        $sheet->setCellValue('C' . $row, $item->kategori);
        $sheet->setCellValue('D' . $row, $item->jumlah);
        $row++;
    }

    $sheet->getStyle("B" . ($row - count($pengeluaran) - 1) . ":D" . ($row - 1))
        ->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

    /* ================= UKURAN KOLOM ================= */
    foreach (['B' => 15, 'C' => 30, 'D' => 20] as $col => $width) {
        $sheet->getColumnDimension($col)->setWidth($width);
    }

    $filename = 'laporan_keuangan_' . $bulan . '.xlsx';
    $writer = new Xlsx($spreadsheet);

    return response()->streamDownload(function () use ($writer) {
        $writer->save('php://output');
    }, $filename, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ]);
}

}
