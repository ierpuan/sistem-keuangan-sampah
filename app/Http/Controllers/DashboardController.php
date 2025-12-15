<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Tagihan;
use App\Models\TransaksiPembayaran;
use App\Models\Pengeluaran;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $bulanIni = Carbon::now();

        // Statistik utama
        $totalPelanggan = Pelanggan::aktif()->count();
        $tagihanBelumLunasCount = Tagihan::whereIn('status', ['Belum Bayar', 'Tunggakan'])->count();

        // Hitung total tunggakan dari selisih tagihan dan yang sudah dibayar
        $totalTunggakan = Tagihan::whereIn('status', ['Belum Bayar', 'Tunggakan'])
            ->selectRaw('SUM(jml_tagihan_pokok - COALESCE(total_sudah_bayar, 0)) as total')
            ->value('total');

        $pemasukanBulanIni = TransaksiPembayaran::whereMonth('tgl_bayar', $bulanIni->month)
            ->whereYear('tgl_bayar', $bulanIni->year)
            ->sum('jml_bayar_input');

        $pengeluaranBulanIni = Pengeluaran::whereMonth('tanggal_pengeluaran', Carbon::now()->month)
        ->whereYear('tanggal_pengeluaran', Carbon::now()->year)
        ->sum('jumlah');

        // Gabungkan ke dalam array $stats untuk dipakai di blade
        $stats = [
            'total_pelanggan' => $totalPelanggan,
            'tagihan_belum_lunas' => $tagihanBelumLunasCount,
            'total_tunggakan' => $totalTunggakan,
            'pemasukan_bulan_ini' => $pemasukanBulanIni,
            'pengeluaran_bulan_ini' => $pengeluaranBulanIni,
        ];

        // Transaksi terbaru
        $transaksi_terbaru = TransaksiPembayaran::with(['tagihan.pelanggan', 'pengguna'])
            ->orderBy('tgl_bayar', 'desc')
            ->limit(5)
            ->get();

        // Tagihan jatuh tempo dalam 7 hari
        $tagihan_jatuh_tempo = Tagihan::whereBetween('jatuh_tempo', [
                $bulanIni->copy()->startOfDay(),
                $bulanIni->copy()->addDays(7)->endOfDay()
            ])
            ->with('pelanggan')
            ->orderBy('jatuh_tempo', 'asc')
            ->get();

        return view('dashboard', compact(
            'stats',
            'transaksi_terbaru',
            'tagihan_jatuh_tempo'
        ));
    }
}
