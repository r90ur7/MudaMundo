<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Especie;
use App\Models\Tipo;
use App\Models\MudaStatus;

class Mudas extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'tipos_id',
        'muda_status_id',
        'especie_id',
        'nome',
        'descricao',
        'quantidade',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'foto_url',
        'donated_at',
        'donated_to',
        'disabled_at',
        'modo_solicitacao', // novo campo para doação ou permuta
        'original_user_id', // novo campo para registrar criador original
    ];

    /**
     * Cast attributes to proper types.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'donated_at' => 'datetime',
        'donated_to' => 'integer',
    ];

    /**
     * Relacionamento com o usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com a espécie.
     */
    public function especie()
    {
        return $this->belongsTo(Especie::class);
    }

    /**
     * Relacionamento com o tipo.
     */
    public function tipo()
    {
        return $this->belongsTo(Tipo::class, 'tipos_id');
    }

    /**
     * Relacionamento com o status.
     */
    public function status()
    {
        return $this->belongsTo(MudaStatus::class, 'muda_status_id', 'id');
    }

    /**
     * Retorna os usuários que favoritaram esta muda
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favoritos', 'muda_id', 'user_id')
                    ->withTimestamps();
    }
}
