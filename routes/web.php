<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LokasiPelangganController;
use App\Http\Controllers\PenggunaController;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Protected Routes (semua login bisa akses dashboard)
Route::middleware(['auth'])->group(function () {

    // Dashboard (Petugas & Admin)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /* ======================================================
    |                PETUGAS + ADMIN (Tagihan, Pembayaran, Lokasi)
    ======================================================= */
    Route::middleware(['role:Petugas,Admin'])->group(function () {

        // Tagihan
        Route::post('tagihan/generate-bulk', [TagihanController::class, 'generateBulk'])
            ->name('tagihan.generate-bulk');
        Route::get('tagihan/pdf', [TagihanController::class, 'downloadPdf'])
            ->name('tagihan.pdf');
        Route::get('tagihan/cetak-periode', [TagihanController::class, 'cetakPeriode'])
        ->name('tagihan.cetak.periode');
        Route::resource('tagihan', TagihanController::class);

        // Pembayaran
        Route::get('pembayaran/create/{id_tagihan}', [PembayaranController::class, 'create'])
            ->name('pembayaran.create');
        Route::post('pembayaran', [PembayaranController::class, 'store'])
            ->name('pembayaran.store');
        // Route edit dan update pembayaran
        Route::get('/pembayaran/{id}/edit', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
        Route::put('/pembayaran/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');
        Route::delete('/pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');

        // Export Semua Bukti Pembayaran
        // Route::get('/export/semua-bukti-pembayaran',
        //     [PembayaranController::class, 'exportSemuaBuktiPembayaranPDF']
        // )->name('pembayaran.export.semua');

        // Lokasi pelanggan
        Route::get('lokasi-pelanggan', [LokasiPelangganController::class, 'index'])
            ->name('lokasi.index');

    });


    /* ======================================================
    |                     ADMIN ONLY
    ======================================================= */
    Route::middleware(['role:Admin'])->group(function () {

        // Pelanggan
        Route::resource('pelanggan', PelangganController::class);

        // Pengeluaran
        Route::resource('pengeluaran', PengeluaranController::class);

        // Laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.export.pdf');
        Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');

        // Pengguna
        Route::resource('pengguna', PenggunaController::class);
        Route::post('pengguna/{pengguna}/reset-password',
            [PenggunaController::class, 'resetPassword']
        )->name('pengguna.reset-password');
    });

});