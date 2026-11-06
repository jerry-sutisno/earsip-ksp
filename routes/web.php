<?php

use App\Http\Controllers\ArsipDokumenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KlasifikasiArsipController;
use App\Http\Controllers\LokasiArsipController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard', [
            'totalAnggota' => DB::table('anggota')->count(),
            'totalArsip' => DB::table('arsip_dokumen')->count(),
            'totalMacet' => DB::table('transaksi_pinjaman')->where('status_pinjaman', 'macet')->count(),
        ]);
    })->name('dashboard');

    // Dilihat petugas dan pimpinan
    Route::middleware('role:petugas,pimpinan')->group(function () {
        Route::get('/arsip', [ArsipDokumenController::class, 'index'])->name('arsip.index');
        Route::resource('klasifikasi', KlasifikasiArsipController::class)
            ->only(['index'])->parameters(['klasifikasi' => 'klasifikasi']);
        Route::resource('lokasi', LokasiArsipController::class)
            ->only(['index'])->parameters(['lokasi' => 'lokasi']);
    });

    // Hanya petugas arsip yang boleh mengubah data
    Route::middleware('role:petugas')->group(function () {
        Route::get('/arsip/create', [ArsipDokumenController::class, 'create'])->name('arsip.create');
        Route::post('/arsip', [ArsipDokumenController::class, 'store'])->name('arsip.store');
        Route::resource('klasifikasi', KlasifikasiArsipController::class)
            ->except(['index', 'show'])->parameters(['klasifikasi' => 'klasifikasi']);
        Route::resource('lokasi', LokasiArsipController::class)
            ->except(['index', 'show'])->parameters(['lokasi' => 'lokasi']);
    });

    Route::view('/approval', 'halaman', ['judul' => 'Approval Pimpinan (placeholder Sprint 4)'])
        ->middleware('role:pimpinan');
});