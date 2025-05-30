<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

// Canal privado para chat de solicitação
Broadcast::channel('chat.{solicitacaoId}', function ($user = null, $solicitacaoId) {
    Log::info('[Broadcast][DEBUG] Canal chat.' . $solicitacaoId, [
        'user_id' => $user ? $user->id : null,
        'user' => $user,
        'auth_check' => \Illuminate\Support\Facades\Auth::check(),
        'auth_user' => \Illuminate\Support\Facades\Auth::user(),
        'session_id' => session()->getId(),
        'session_all' => session()->all(),
        'cookies' => request()->cookies->all(),
        'headers' => request()->headers->all(),
    ]);
    Log::info('[Broadcast] Canal chat.' . $solicitacaoId . ' | user: ' . ($user ? $user->id : 'NÃO AUTENTICADO'));
    if (!$user) return false;
    $solicitacao = \App\Models\Solicitacao::find($solicitacaoId);
    Log::info('[Broadcast] Autorizando user_id=' . $user->id . ' para canal chat.' . $solicitacaoId . ' | solicitacao: ' . ($solicitacao ? $solicitacao->id : 'N/A'));
    if (!$solicitacao) return false;
    if ($solicitacao->user_id === $user->id) return true;
    if ($solicitacao->mudas && $solicitacao->mudas->user_id === $user->id) return true;
    // Permite acesso ao dono original da muda
    if ($solicitacao->mudas && $solicitacao->mudas->original_user_id === $user->id) return true;
    return false;
});

// Canal privado global para notificações do usuário (sempre permite)
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
