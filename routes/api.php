<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
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
Route::post('/update',[SessionController::class,'update'])->middleware('auth:sanctum');;


Route::get('/categories',[CategoryController::class,'index']);
Route::get('/categories/{id}',[CategoryController::class,'show'])->middleware('auth:sanctum');;
Route::get('/category/search/{id}',[CategoryController::class,'search'])->middleware('auth:sanctum');;

Route::get('/stores',[StoreController::class,'index']);
Route::get('/stores/{id}',[StoreController::class,'show'])->middleware('auth:sanctum');;
Route::get('/store/search/{id}',[StoreController::class,'search'])->middleware('auth:sanctum');;

Route::get('/products',[ProductController::class,'index'])->middleware('auth:sanctum');
Route::get('/products/{id}',[ProductController::class,'show'])->middleware('auth:sanctum');
Route::get('/search', [ProductController::class,'search'])->middleware('auth:sanctum');;

Route::post('/Favorite/add/{id}',[FavoriteController::class,'store'])->middleware('auth:sanctum');
Route::get('/Favorites',[FavoriteController::class,'index'])->middleware('auth:sanctum');
Route::delete('/Favorite/delete/{id}',[FavoriteController::class,'destroy'])->middleware('auth:sanctum');

Route::post('/Cart/add/{id}',[CartController::class,'addToCart'])->middleware('auth:sanctum');
Route::get('/Cart/show',[CartController::class,'show'])->middleware('auth:sanctum');
Route::post('/Cart/delete/one/{id}',[CartController::class,'removeOneItem'])->middleware('auth:sanctum');
Route::delete('/Cart/delete/{id}',[CartController::class,'destroy'])->middleware('auth:sanctum');
Route::get('/cart/search', [CartController::class,'search'])->middleware('auth:sanctum');

Route::get('/orders',[OrderController::class,'index'])->middleware('auth:sanctum');
Route::get('/orders/{id}',[OrderController::class,'show'])->middleware('auth:sanctum');
Route::get('/order/check',[OrderController::class,'checkOrder'])->middleware('auth:sanctum');
Route::post('/order',[OrderController::class,'order'])->middleware('auth:sanctum');
Route::delete('/order/delete/{id}',[OrderController::class,'destroy'])->middleware('auth:sanctum');
Route::post('/order/edit/{id}',[OrderController::class,'update'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
