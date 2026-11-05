<?php

use App\Http\Controllers\AuthController;
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

    Route::view('/arsip', 'halaman', ['judul' => 'Daftar Arsip (placeholder Sprint 2)'])
        ->middleware('role:petugas,pimpinan');

    Route::view('/approval', 'halaman', ['judul' => 'Approval Pimpinan (placeholder Sprint 4)'])
        ->middleware('role:pimpinan');
});