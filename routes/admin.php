<?php

use Azuriom\Plugin\WebMap\Controllers\Admin\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/settings', [SettingController::class, 'show'])->name('settings');
Route::post('/settings', [SettingController::class, 'save'])->name('settings.save');
