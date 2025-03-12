<?php

use App\Http\Controllers\ZKTecoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('zkteco')->name('zkteco.')->group(function () {
    Route::get('/', [ZKTecoController::class, 'index'])->name('index');
    Route::get('/create', [ZKTecoController::class, 'create'])->name('create');
    Route::post('/store', [ZKTecoController::class, 'store'])->name('store');
    Route::get('/check-connection/{id}', [ZKTecoController::class, 'checkConnection'])->name('check-connection');
});
