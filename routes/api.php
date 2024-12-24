<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register',[RegisterController::class,'store']);
Route::get('/show',[RegisterController::class,'show'])->middleware('auth:sanctum');

Route::post('/login',[SessionController::class,'store']);

Route::get('/categories',[CategoryController::class,'index']);
Route::get('/categories/{id}',[CategoryController::class,'show']);

Route::get('/stores',[StoreController::class,'index']);
Route::get('/stores/{id}',[StoreController::class,'show']);

Route::get('/products',[ProductController::class,'index']);
Route::get('/products/{id}',[ProductController::class,'show']);
Route::get('/search', [ProductController::class,'search']);

Route::post('/Favorite/add/{id}',[FavoriteController::class,'store'])->middleware('auth:sanctum');
Route::get('/Favorites',[FavoriteController::class,'index'])->middleware('auth:sanctum');
Route::delete('/Favorite/delete/{id}',[FavoriteController::class,'destroy'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
