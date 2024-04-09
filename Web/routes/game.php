<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Game\HomeController;

//https://minigame.plats.network/api/v1/day1-codes
// Demo
Route::get('game/{id}', [HomeController::class, 'miniGame'])->name('game.miniGame');

Route::post('game/result', [HomeController::class, 'updateResult'])->name('game.updateResult');

//Local. https://cws.plats.test/api/v1/day1-codes

// Api
Route::get('/api/v1/day1-codes', [HomeController::class, 'dayOne'])->name('game.dayOne');

Route::get('/api/v1/day2-codes', [HomeController::class, 'dayTwo'])->name('game.dayTwo');

Route::get('/api/v1/day3-codes', [HomeController::class, 'dayThree'])->name('game.dayThree');

Route::get('/api/v1/booth-codes', [HomeController::class, 'boothCodes'])->name('game.boothCodes');

