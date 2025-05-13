<?php

use Illuminate\Support\Facades\Broadcast;

// Canal privado para chat de solicitação
Broadcast::channel('chat.{solicitacaoId}', function ($user, $solicitacaoId) {
    // Permite acesso se o usuário for o solicitante OU o dono da muda
    $solicitacao = \App\Models\Solicitacao::find($solicitacaoId);
    if (!$solicitacao) return false;
    if ($solicitacao->user_id === $user->id) return true;
    if ($solicitacao->mudas && $solicitacao->mudas->user_id === $user->id) return true;
    return false;
});
