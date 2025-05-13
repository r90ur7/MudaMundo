<?php

namespace App\Events;

use App\Models\solicitacao_mensagem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NovaMensagemChat implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mensagem;

    public function __construct(solicitacao_mensagem $mensagem)
    {
        $this->mensagem = $mensagem->load('user');
    }

    public function broadcastOn()
    {
        // Canal privado por solicitação
        return new PrivateChannel('chat.' . $this->mensagem->solicitacao_id);
    }

    public function broadcastWith()
    {
        return [
            'mensagem' => $this->mensagem->toArray(),
        ];
    }
}
