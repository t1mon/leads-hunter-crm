<?php

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

Route::prefix('auth')->namespace('Api\Auth')->group(function () {

    Route::post('register', [\App\Http\Controllers\Api\Auth\AuthController::class,'createUser']);
    Route::post('login', [\App\Http\Controllers\Api\Auth\AuthController::class,'loginUser']);

    Route::fallback(function () {
        return response()->json(['message' => 'Not Found.'], 404);
    });

});



