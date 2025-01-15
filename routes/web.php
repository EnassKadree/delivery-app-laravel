<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
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
Route::put('/store/update/{id}', [StoreController::class, 'update'])->name('admin.store.update');
Route::delete('/store/delete/{id}', [StoreController::class, 'destroy'])->name('admin.store.destroy');
Route::post('/store/create', [StoreController::class, 'store'])->name('admin.store.store');

// Product
Route::get('/products', [ProductController::class, 'indexweb'])->name('admin.product.indexweb');
Route::get('/product/create', [ProductController::class, 'create'])->name('admin.product.create');
Route::get('/product/edit/{product}', [ProductController::class, 'edit'])->name('admin.product.edit');
Route::put('/product/update/{id}', [ProductController::class, 'update'])->name('admin.product.update');
Route::delete('/product/delete/{id}', [ProductController::class, 'destroy'])->name('admin.product.destroy');
Route::post('/product/create', [ProductController::class, 'store'])->name('admin.product.store');

// Category
Route::get('/categories', [CategoryController::class, 'indexweb'])->name('admin.category.indexweb');
Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
Route::get('/category/edit/{category}', [CategoryController::class, 'edit'])->name('admin.category.edit');
Route::put('/category/update/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
Route::delete('/category/delete/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');
Route::post('/category/create', [CategoryController::class, 'store'])->name('admin.category.store');

// Order
Route::get('/orders', [OrderController::class, 'indexweb'])->name('admin.order.indexweb');
Route::get('/order/edit/{order}', [OrderController::class, 'edit'])->name('admin.order.edit');
Route::put('/order/update/{id}', [OrderController::class, 'updateweb'])->name('admin.order.updateweb');

});

Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
