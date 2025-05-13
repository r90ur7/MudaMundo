<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitacao_mensagem extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitacaoMensagemFactory> */
    use HasFactory;

    protected $table = 'solicitacao_mensagens';

    protected $fillable = [
        'solicitacao_id',
        'user_id',
        'mensagem',
    ];

    public function solicitacao()
    {
        return $this->belongsTo(\App\Models\Solicitacao::class, 'solicitacao_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
