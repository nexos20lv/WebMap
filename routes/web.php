<?php

use Azuriom\Plugin\WebMap\Controllers\WebMapController;
use Azuriom\Plugin\WebMap\Controllers\ProxyController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [WebMapController::class, 'index'])->name('index');
Route::get('/proxy/{path?}', [ProxyController::class, 'proxy'])
    ->name('proxy')
    ->where('path', '.*');

