<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MudasController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Storage;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::resource('mudas', MudasController::class);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas para preenchimento automático de endereço
Route::middleware('auth')->group(function () {
    Route::get('/user/address', [App\Http\Controllers\UserController::class, 'getUserAddress']);
    Route::get('/user/last-address', [App\Http\Controllers\UserController::class, 'getLastUsedAddress']);
});

// Rota para servir imagens do storage diretamente
Route::get('/imagem-muda/{filename}', function ($filename) {
    $path = storage_path('app/public/mudas/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $file = file_get_contents($path);
    $type = mime_content_type($path);

    return response($file, 200)->header('Content-Type', $type);
})->name('imagem.muda');

require __DIR__.'/auth.php';
