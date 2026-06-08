<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\LaboranController;
use Illuminate\Support\Facades\Route;

// Rute Tamu (Hanya bisa diakses jika BELUM login)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute Terproteksi (Harus LOGIN terlebih dahulu)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Group Halaman Admin (Hanya Role Admin)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return '<h1>Halo Admin! Ini halaman kelola Fasilitas (CRUD).</h1>';
        })->name('dashboard');
    });

    // Group Halaman Laboran (Hanya Role Laboran)
    Route::middleware('role:laboran')->prefix('laboran')->name('laboran.')->group(function () {
        Route::get('/dashboard', [LaboranController::class, 'dashboard'])->name('dashboard');
        Route::patch('/reservations/{id}/status', [LaboranController::class, 'updateStatus'])->name('reservations.status');
    });

    // Group Halaman Mahasiswa (Hanya Role Mahasiswa)
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
        Route::post('/reservations', [MahasiswaController::class, 'store'])->name('reservations.store'); // <-- TAMBAHKAN BARIS INI!
    });
});