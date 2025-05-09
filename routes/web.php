<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokBarangController;
use App\Http\Controllers\ProdukJadiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PermintaanBahanBakuController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\PenjualanBarangController;
use App\Http\Controllers\LaporanPembelianController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\LaporanProduksiController;

Auth::routes(['register' => false]);
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::resource('dashboard', DashboardController::class);
    Route::resource('permintaanbahanbaku', PermintaanBahanBakuController::class);
    Route::resource('produksi', ProduksiController::class);
    Route::resource('stokbarang', StokBarangController::class);
    Route::resource('user', UserController::class);
    Route::resource('supplier', SupplierController::class);
    Route::resource('bahanbaku', BahanBakuController::class);
    Route::resource('produkjadi', ProdukJadiController::class);
    Route::resource('pelanggan', PelangganController::class);
    Route::resource('penjualan', PenjualanBarangController::class);
    Route::resource('pembelian', PembelianController::class);
    Route::get('laporan/pembelian', [LaporanPembelianController::class, 'index'])->name('laporan.pembelian.index');
    Route::get('laporan/pembelian/generate', [LaporanPembelianController::class, 'generate'])->name('laporan.pembelian.generate');
    Route::get('laporan/penjualan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan.index');
    Route::get('laporan/penjualan/generate', [LaporanPenjualanController::class, 'generate'])->name('laporan.penjualan.generate');
    Route::get('laporan/produksi', [LaporanProduksiController::class, 'index'])->name('laporan.produksi.index');
    Route::get('laporan/produksi/generate', [LaporanProduksiController::class, 'generate'])->name('laporan.produksi.generate');
    Route::resource('stokbarang', StokBarangController::class);
});



