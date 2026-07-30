<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\PasienController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PerawatController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\DiagnosaController;
use App\Http\Controllers\StokTransaksiController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Data Master (Admin Only later)
    Route::resource('poli', PoliController::class);
    Route::resource('dokter', DokterController::class);
    Route::resource('perawat', PerawatController::class);
    Route::resource('obat', ObatController::class);

    // Manajemen Pasien
    Route::resource('pasien', PasienController::class);

    // Manajemen Pendaftaran/Antrian
    Route::resource('pendaftaran', PendaftaranController::class);

    // Modul Pemeriksaan & Diagnosa (Dokter)
    Route::resource('diagnosa', DiagnosaController::class);

    // Manajemen Stok Obat Farmasi (Apotek)
    Route::resource('stok_transaksi', StokTransaksiController::class);



    // Modul Laporan (Admin/Leader)
    Route::group(['prefix' => 'laporan', 'as' => 'laporan.'], function () {
        Route::get('/', [App\Http\Controllers\LaporanController::class, 'index'])->name('index');
        Route::get('/kunjungan', [App\Http\Controllers\LaporanController::class, 'kunjungan'])->name('kunjungan');
        Route::get('/diagnosa', [App\Http\Controllers\LaporanController::class, 'diagnosa'])->name('diagnosa');
        Route::get('/obat', [App\Http\Controllers\LaporanController::class, 'obat'])->name('obat');
    });
});