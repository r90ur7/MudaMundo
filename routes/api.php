<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// API de chats do usuário
Route::middleware('auth:sanctum')->get('/chats', [\App\Http\Controllers\SolicitacaoMensagemController::class, 'userChats']);


Route::get('all-users', function () {
    return response()->json(\App\Models\User::all());
});
