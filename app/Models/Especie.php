<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    // Adicionando campos preenchíveis
    protected $fillable = [
        'nome',
        'descricao'
    ];
}
