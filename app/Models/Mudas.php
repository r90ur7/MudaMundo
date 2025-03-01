<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'bairro',
        'cidade',
        'uf',
        'donated_at',
        'disabled_at'
    ];

    public function status()
    {
        return $this->belongsTo(MudaStatus::class, 'muda_status_id')
            ->where('table', 'muda_status');
    }
}