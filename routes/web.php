<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\DashboardController;

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
});

// Authentication Routes
Route::get('/', function () {
    return view('auth.login');
})->name('login');
Route::post('/login', [SessionController::class, 'storeWeb'])->name('login.store');


Route::prefix('admin')->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
//   Store
Route::get('/stores', [StoreController::class, 'indexweb'])->name('admin.store.indexweb');
Route::get('/store/create', [StoreController::class, 'create'])->name('admin.store.create');
Route::get('/store/edit/{store}', [StoreController::class, 'edit'])->name('admin.store.edit');
Route::post('/store/update/{id}', [StoreController::class, 'update'])->name('admin.store.update');
Route::delete('/store/delete/{id}', [StoreController::class, 'destroy'])->name('admin.store.destroy');
Route::post('/store/create', [StoreController::class, 'store'])->name('admin.store.store');
});

Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
