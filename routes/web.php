<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MudasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;

// não precisa de autenticação
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('mudas/index', [MudasController::class, 'index'])->name('mudas.index');

//rota para imagens
Route::get('mudas/get-file-image', [MudasController::class, 'getFileImage'])->name('mudas.getFileImage');

// Needs auth
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::controller(DashboardController::class)->middleware('verified')->prefix('dashboard')->group(function () {
        Route::get('', 'index')->name('dashboard');
    });

    // Profile
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('', 'edit')->name('edit');
        Route::patch('', 'update')->name('update');
        Route::delete('', 'destroy')->name('destroy');
    });

    // User
    Route::controller(UserController::class)->prefix('user')->name('user.')->group(function() {
        Route::get('address',  'getUserAddress')->name('getUserAddress');
        Route::get('last-address',  'getLastUsedAddress')->name('getLastUsedAddress');
    });

    // Mudas
    Route::controller(MudasController::class)->prefix('mudas')->name('mudas.')->group(function () {
        Route::get('create', 'create')->name('create');
        Route::get('show/{muda}', 'show')->name('show');
        Route::post('store', 'store')->name('store');
        Route::get('{id}/edit', 'edit')->name('edit');
        Route::put('{id}/update', 'update')->name('update');
        Route::delete('{id}/destroy', 'destroy')->name('destroy');
    });
});

require __DIR__.'/auth.php';
