<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo extends Model
{
    // Adicionando campos preenchíveis
    protected $fillable = [
        'nome',
        'nome_cientifico',
        'descricao'
    ];
}
