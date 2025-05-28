<?php

namespace App\Events;

use App\Models\solicitacao_mensagem;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        Log::info('[Chat] broadcastOn chamado para canal: chat.' . $this->mensagem->solicitacao_id . ' | mensagem_id=' . $this->mensagem->id . ' | user_id=' . $this->mensagem->user_id);
        return new PrivateChannel('chat.' . $this->mensagem->solicitacao_id);
    }

    public function broadcastWith()
    {
        $mensagem = $this->mensagem->toArray();
        $mensagem['user'] = $this->mensagem->user ? $this->mensagem->user->toArray() : null;
        if ($this->mensagem->user) {
            $mensagem['user']['profile_photo_url'] = $this->mensagem->user->profile_photo_url;
        }
        return [
            'mensagem' => $mensagem,
        ];
    }
}
