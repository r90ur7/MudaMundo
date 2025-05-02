<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MudasController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SolicitacoesController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('mudas/index', [MudasController::class, 'index'])->name('mudas.index');

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
        Route::get('mudas/filter', 'minhasMudas')->name('mudas.filter');
        Route::post('update-photo', 'updatePhoto')->name('update.photo');
        Route::get('photo', 'getPhoto')->name('photo');
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
        Route::get('{muda}/edit', 'edit')->name('edit');
        Route::put('{muda}/update', 'update')->name('update');
        Route::delete('{muda}/destroy', 'destroy')->name('destroy');
        Route::patch('{muda}/release', 'release')->name('release');
    });

    // Solicitações de mudas
    Route::get('solicitacoes/checkout/{muda}', [SolicitacoesController::class, 'checkout'])->name('solicitacoes.checkout');
    Route::post('solicitacoes', [SolicitacoesController::class, 'store'])->name('solicitacoes.store');
    Route::get('solicitacoes/{solicitacao}', [SolicitacoesController::class, 'show'])->name('solicitacoes.show');
    Route::patch('solicitacoes/{solicitacao}/accept', [SolicitacoesController::class, 'accept'])->name('solicitacoes.accept');
    Route::patch('solicitacoes/{solicitacao}/reject', [SolicitacoesController::class, 'reject'])->name('solicitacoes.reject');
    Route::patch('solicitacoes/{solicitacao}/negotiate', [SolicitacoesController::class, 'negotiate'])->name('solicitacoes.negotiate');
});

require __DIR__.'/auth.php';
