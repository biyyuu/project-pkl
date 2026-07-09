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

// Barang Keluar
Route::middleware('auth')->group(function () {
    Route::get('/barang-keluar', [ItemOutgoingController::class, 'index'])
        ->name('item-outgoing.index');
    Route::post('/barang-keluar', [ItemOutgoingController::class, 'store'])
        ->name('item-outgoing.store');
    Route::delete('/barang-keluar/{itemOutgoing}', [ItemOutgoingController::class, 'destroy'])
        ->name('item-outgoing.destroy');
        
    // AJAX endpoins
    Route::post('/ajax/items', [\App\Http\Controllers\ItemController::class, 'storeAjax'])->name('items.storeAjax');
    Route::post('/ajax/borrowers', [\App\Http\Controllers\BorrowerController::class, 'storeAjax'])->name('borrowers.storeAjax');
});
