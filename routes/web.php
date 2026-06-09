<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\LaboranController;
use Illuminate\Support\Facades\Route;

// Rute Tamu (Belum Login)
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Rute Terproteksi (Harus Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Group Halaman Laboran (Hanya Role Laboran)
    Route::middleware('role:laboran')->prefix('laboran')->name('laboran.')->group(function () {
        Route::get('/dashboard', [LaboranController::class, 'dashboard'])->name('dashboard');
        Route::patch('/reservations/{id}/status', [LaboranController::class, 'updateStatus'])->name('reservations.status');
    });

    // Group Halaman Mahasiswa (Hanya Role Mahasiswa)
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('dashboard');
        Route::post('/reservations', [MahasiswaController::class, 'store'])->name('reservations.store');
    });
});