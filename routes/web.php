<?php

use App\Http\Controllers\AuthController;
// Import Controller baru untuk Catatan Keuangan
use App\Http\Controllers\FinancialRecordController; 
use Illuminate\Support\Facades\Route;

// Rute ROOT: Mengarahkan langsung ke halaman beranda aplikasi
// Ini adalah praktik umum jika halaman depan adalah halaman setelah login.
Route::get('/', function () {
    return redirect()->route('app.home');
});

// Rute ini hanya dapat diakses oleh pengguna yang BELUM login
Route::group(['prefix' => 'auth'], function () {
    // Menampilkan halaman login
    Route::get('/login', [AuthController::class, 'login'])->name('auth.login');
    
    // Menampilkan halaman register
    Route::get('/register', [AuthController::class, 'register'])->name('auth.register');
});

// --- Kelompok Rute APLIKASI UTAMA (Akses AUTH) ---
// Rute ini dilindungi oleh middleware 'check.auth' (yang dulu 'auth')
Route::group(['prefix' => 'app', 'middleware' => 'check.auth'], function () {
    
    // Halaman Beranda / Dashboard Catatan Keuangan
    Route::get('/home', [FinancialRecordController::class, 'index'])->name('app.home');

    // Rute Logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    
    // Rute Detail Catatan Keuangan
    // Menggunakan Model Binding implisit: {financialRecord} akan mencari Model FinancialRecord berdasarkan ID.
    // Ganti nama rute dari app.todos.detail menjadi app.financial.detail
    Route::get('/financial-records/{financialRecord}', [FinancialRecordController::class, 'detail'])->name('app.financial.detail');
});