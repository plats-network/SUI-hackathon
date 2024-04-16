<?php

use App\Http\Controllers\Auth\Admin\Login;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
|
|
*/

// route for get shortener url
Route::get('s/{shortener_url}', [\App\Http\Controllers\UrlController::class, 'shortenLink'])->name('shortener-url');


