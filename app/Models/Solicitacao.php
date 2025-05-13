<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitacoesFactory> */
    use HasFactory;

    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'solicitacoes';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'muda_id',
        'solicitacao_tipos_id',
        'solicitacao_status_id',
        'muda_troca_id',
        'mensagem',
        'rejected_by',
        'rejected_at',
        'finished_at',
        'accepted_at',
        'confirmed_at',
        'canceled_at'
    ];

    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rejected_at' => 'datetime',
        'finished_at' => 'datetime',
        'accepted_at' => 'datetime',
        'confirmed_at' => 'datetime',
    ];

    /**
     * Relacionamento com a muda solicitada
     */
    public function mudas()
    {
        return $this->belongsTo(\App\Models\Mudas::class, 'muda_id');
    }

    /**
     * Relacionamento com a muda de troca (em caso de permuta)
     */
    public function mudaTroca()
    {
        return $this->belongsTo(\App\Models\Mudas::class, 'muda_troca_id');
    }

    /**
     * Relacionamento com o tipo de solicitação
     */
    public function tipo()
    {
        return $this->belongsTo(\App\Models\solicitacao_tipos::class, 'solicitacao_tipos_id');
    }

    /**
     * Relacionamento com o status da solicitação
     */
    public function status()
    {
        return $this->belongsTo(\App\Models\solicitacao_status::class, 'solicitacao_status_id');
    }

    /**
     * Relacionamento com o usuário solicitante
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Relacionamento com as mensagens da solicitação
     */
    public function mensagens()
    {
        return $this->hasMany(\App\Models\solicitacao_mensagem::class, 'solicitacao_id');
    }
}
