<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;

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
Route::post('auth/login', [LoginController::class,'login']);

Route::get('products', [ProductController::class,'index']);
Route::post('products/{id}', [ProductController::class,'show']);

Route::group(['middleware'=>[ 'auth.optional']], function(){

    Route::get('cart', [CartController::class,'index']);
    Route::post('cart', [CartController::class,'store']);
    Route::put('cart/{id}', [CartController::class,'update']);
    Route::delete('cart/{id}', [CartController::class,'destroy']);

 });


Route::group(['middleware'=>[ 'auth:sanctum']], function(){

    Route::post('/products', [ProductController::class,'store']);
    Route::delete('/products/{id}', [ProductController::class,'destroy']);

 });




