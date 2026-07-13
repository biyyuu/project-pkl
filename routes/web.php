<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemOutgoingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Login
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('guest');

// Logout
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');

// Daftar Barang
Route::middleware('auth')->group(function () {
    Route::get('/daftar-barang', [\App\Http\Controllers\ItemController::class, 'index'])->name('item');
    Route::post('/daftar-barang', [\App\Http\Controllers\ItemController::class, 'store'])->name('items.store')->middleware('role:admin');
    Route::put('/daftar-barang/{item}', [\App\Http\Controllers\ItemController::class, 'update'])->name('items.update')->middleware('role:admin');
    Route::delete('/daftar-barang/{item}', [\App\Http\Controllers\ItemController::class, 'destroy'])->name('items.destroy')->middleware('role:admin');
});

// Barang Keluar
Route::middleware('auth')->group(function () {
    Route::get('/barang-keluar', [ItemOutgoingController::class, 'index'])
        ->name('item-outgoing.index');
    Route::post('/barang-keluar', [ItemOutgoingController::class, 'store'])
        ->name('item-outgoing.store')->middleware('role:admin|kasub');
    Route::put('/barang-keluar/{itemOutgoing}', [ItemOutgoingController::class, 'update'])
        ->name('item-outgoing.update')->middleware('role:admin');
    Route::delete('/barang-keluar/{itemOutgoing}', [ItemOutgoingController::class, 'destroy'])
        ->name('item-outgoing.destroy')->middleware('role:admin');
        
    // Approvals
    Route::get('/approval', [\App\Http\Controllers\ApprovalController::class, 'index'])->name('approval.index');
    Route::post('/approval/{itemOutgoing}/approve', [\App\Http\Controllers\ApprovalController::class, 'approve'])->name('approval.approve');
    Route::post('/approval/{itemOutgoing}/reject', [\App\Http\Controllers\ApprovalController::class, 'reject'])->name('approval.reject');

    // History Page
    Route::get('/history', [\App\Http\Controllers\DashboardController::class, 'history'])->name('history.index');

    // Reports
    Route::get('/export/laporan', [DashboardController::class, 'exportPdf'])->name('export.pdf');

    // AJAX endpoints
    Route::post('/ajax/items', [\App\Http\Controllers\ItemController::class, 'storeAjax'])->name('items.storeAjax');
    Route::post('/ajax/borrowers', [\App\Http\Controllers\BorrowerController::class, 'storeAjax'])->name('borrowers.storeAjax');
});
