<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    //logout
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('login', [AuthController::class, 'login']);
    //products
    Route::apiResource('/products', ProductController::class);
    Route::post('/filter',[ProductController::class,'filter']);
    // categories
    Route::get('/categories', [CategoryController::class, 'index']);
    //payment
    Route::post('/payment',[CartController::class,'payment']);
});

Route::post('register',[AuthController::class,'register']);
Route::post('login', [AuthController::class, 'login']);

