<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\CategoryController;
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

Route::post('/register',[RegisterController::class,'store'])->name('verification.notice');;
Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verifyEmail'])->middleware(['auth:sanctum', 'signed'])->name('verification.verify');
Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return response()->json(['message' => 'Verification email resent successfully.']);
})->middleware('auth:sanctum')->name('verification.send');
Route::get('/show',[RegisterController::class,'show'])->middleware('auth:sanctum');

Route::post('/login',[SessionController::class,'store']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});