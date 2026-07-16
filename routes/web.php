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

// All authenticated routes
Route::middleware('auth')->group(function () {

    // === Daftar Barang ===
    Route::get('/daftar-barang', [\App\Http\Controllers\ItemController::class, 'index'])
        ->name('item')
        ->middleware('permission:view-items');

    Route::post('/daftar-barang', [\App\Http\Controllers\ItemController::class, 'store'])
        ->name('items.store')
        ->middleware('permission:create-items');

    Route::put('/daftar-barang/{item}', [\App\Http\Controllers\ItemController::class, 'update'])
        ->name('items.update')
        ->middleware('permission:update-items');

    Route::delete('/daftar-barang/{item}', [\App\Http\Controllers\ItemController::class, 'destroy'])
        ->name('items.destroy')
        ->middleware('permission:delete-items');

    // === Barang Keluar ===
    Route::get('/barang-keluar', [ItemOutgoingController::class, 'index'])
        ->name('item-outgoing.index')
        ->middleware('permission:view-outgoings');

    Route::post('/barang-keluar', [ItemOutgoingController::class, 'store'])
        ->name('item-outgoing.store')
        ->middleware('permission:create-outgoings');

    Route::put('/barang-keluar/{itemOutgoing}', [ItemOutgoingController::class, 'update'])
        ->name('item-outgoing.update')
        ->middleware('permission:update-outgoings');

    Route::delete('/barang-keluar/{itemOutgoing}', [ItemOutgoingController::class, 'destroy'])
        ->name('item-outgoing.destroy')
        ->middleware('permission:delete-outgoings');

    // AJAX endpoints
    Route::post('/ajax/items', [\App\Http\Controllers\ItemController::class, 'storeAjax'])
        ->name('items.storeAjax')
        ->middleware('permission:create-items');

    Route::post('/ajax/borrowers', [\App\Http\Controllers\BorrowerController::class, 'storeAjax'])
        ->name('borrowers.storeAjax')
        ->middleware('permission:create-outgoings');

    // === Approvals ===
    Route::get('/approval', [\App\Http\Controllers\ApprovalController::class, 'index'])
        ->name('approval.index')
        ->middleware('permission:view-approval');

    Route::post('/approval/{itemOutgoing}/approve', [\App\Http\Controllers\ApprovalController::class, 'approve'])
        ->name('approval.approve')
        ->middleware('permission:approve-outgoings');

    Route::post('/approval/{itemOutgoing}/reject', [\App\Http\Controllers\ApprovalController::class, 'reject'])
        ->name('approval.reject')
        ->middleware('permission:reject-outgoings');

    // === History Page ===
    Route::get('/history', [DashboardController::class, 'history'])
        ->name('history.index')
        ->middleware('permission:view-history');
        
    Route::delete('/history/{history}', [DashboardController::class, 'destroyHistory'])
        ->name('history.destroy')
        ->middleware('role:admin');

    // === Reports ===
    Route::get('/export/laporan', [DashboardController::class, 'exportPdf'])
        ->name('export.pdf')
        ->middleware('permission:export-reports');
});
