<?php

use App\Http\Controllers\AuthController;
use App\Http\Middleware\FirebaseJwtMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => FirebaseJwtMiddleware::class], function () {
    Route::get('/orders', [OrderController::class, 'index']);
    Route::put('/orders/{id}',  [OrderController::class, 'update']);
    Route::post('/orders',  [OrderController::class, 'store']);
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});
