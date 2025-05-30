<?php

namespace App\Events;

use App\Models\Solicitacao;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SolicitacaoCriada implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $solicitacao;
    public $preview;

    public function __construct(Solicitacao $solicitacao, $preview = null)
    {
        $this->solicitacao = $solicitacao;
        $this->preview = $preview ?? 'Sua solicitação para a muda "' . ($solicitacao->mudas->nome ?? '') . '" foi enviada!';
    }

    public function broadcastOn()
    {
        // Notifica o solicitante
        return [
            new PrivateChannel('user.' . ($this->solicitacao->user_id ?? 0)),
        ];
    }

    public function broadcastWith()
    {
        return [
            'preview' => $this->preview,
            'solicitacao_id' => $this->solicitacao->id,
            'muda_nome' => $this->solicitacao->mudas->nome ?? '',
            'solicitante_nome' => $this->solicitacao->user->name ?? '',
        ];
    }
}
