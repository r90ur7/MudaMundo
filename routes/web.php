<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MudaController;
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('mudas', MudaController::class);
