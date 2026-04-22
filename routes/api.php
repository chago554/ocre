<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Ruta pública para obtener el token
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetCode']);
Route::post('/password/reset', [ForgotPasswordController::class, 'resetPassword']);


// Rutas protegidas
Route::middleware('auth:api')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    


});