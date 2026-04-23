<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Kasir\DashboardController as KasirDashboard;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Kasir Routes
Route::middleware('auth')->prefix('kasir')->group(function () {
    Route::get('/', [KasirDashboard::class, 'index'])->name('kasir.dashboard');
    Route::post('/transaksi', [KasirDashboard::class, 'store'])->name('kasir.transaksi.store');
    Route::get('/transaksi/{id}', [KasirDashboard::class, 'show'])->name('kasir.transaksi.show');
    Route::get('/transaksi/{id}/print', [KasirDashboard::class, 'print'])->name('kasir.transaksi.print');
});

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Profile Routes
    Route::get('/profil', [ProfileController::class, 'index'])->name('admin.profil');
    Route::post('/profil', [ProfileController::class, 'update'])->name('admin.profil.update');
    Route::post('/profil/password', [ProfileController::class, 'updatePassword'])->name('admin.profil.password');
    
    // Medicine Routes
    Route::get('/obat', [MedicineController::class, 'index'])->name('admin.obat');
    Route::post('/obat', [MedicineController::class, 'store'])->name('admin.obat.store');
    Route::put('/obat/{medicine}', [MedicineController::class, 'update'])->name('admin.obat.update');
    Route::delete('/obat/{medicine}', [MedicineController::class, 'destroy'])->name('admin.obat.destroy');
    Route::post('/obat/import', [MedicineController::class, 'import'])->name('admin.obat.import');

    // Stock Routes
    Route::get('/stok', [StockController::class, 'index'])->name('admin.stok');

    // Supplier Routes
    Route::get('/supplier', [SupplierController::class, 'index'])->name('admin.supplier');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('admin.supplier.store');
    Route::put('/supplier/{supplier}', [SupplierController::class, 'update'])->name('admin.supplier.update');
    Route::delete('/supplier/{supplier}', [SupplierController::class, 'destroy'])->name('admin.supplier.destroy');

    // Report Routes
    Route::get('/laporan', [ReportController::class, 'index'])->name('admin.laporan');

    // User Management Routes
    Route::get('/users', [UserController::class, 'index'])->name('admin.users');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('admin.users.toggle');

    // Settings Routes
    Route::get('/pengaturan', [SettingsController::class, 'index'])->name('admin.pengaturan');
    Route::post('/pengaturan', [SettingsController::class, 'update'])->name('admin.pengaturan.update');
    Route::delete('/pengaturan/logo', [SettingsController::class, 'deleteLogo'])->name('admin.pengaturan.logo.delete');
});

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});
