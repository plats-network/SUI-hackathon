<?php

declare(strict_types=1);

use App\Http\Controllers\Api\v1\Authentication\LoginController;
use App\Http\Controllers\Api\v1\Authentication\LogoutController;
use App\Http\Controllers\Api\v1\Authentication\RegisterController;
use App\Http\Controllers\Api\v1\Authentication\ResetPasswordController;
use App\Http\Controllers\Api\v1\Authentication\VerifyEmailController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $arrApi = [
        'connect-wallet' => [
            'method' => 'POST',
            'url' => '/api/v1/wallet-login',
            'params' => [
                'wallet_address' => 'string',
                'wallet_name' => 'string',
            ],
            'response' => [
                'message' => 'string',
                'token' => 'string',
                'user' => 'object',
            ],
        ],
        'register' => [
            'method' => 'POST',
            'url' => '/api/v1/register',
            'params' => [
                'name' => 'string',
                'email' => 'string',
                'password' => 'string',
                'password_confirmation' => 'string',
            ],
            'response' => [
                'message' => 'string',
                'token' => 'string',
            ],
        ],
    ];
    return [
        'message' => 'Hello World. API Working fine!',
        'date_update' => '2023-10-16',
        'api' => $arrApi
    ];
});

//Authentication routes
Route::post('login', LoginController::class);
Route::post('register', RegisterController::class);

Route::prefix('email')
    ->group(function () {
        Route::post('verification-notification', [VerifyEmailController::class, 'notify']);
        Route::get('verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->name('verification.verify');
    });

Route::prefix('password')
    ->group(function () {
        Route::post('reset-notification', [ResetPasswordController::class, 'notify']);
        Route::post('reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    });

//User routes
Route::middleware('auth:sanctum')
    ->group(function () {
        Route::post('logout', LogoutController::class);

        Route::patch('users/{user}/avatar', [UserController::class, 'updateAvatar']);

        Route::apiResource('users', UserController::class);
    });
