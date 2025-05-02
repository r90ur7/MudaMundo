<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitacao_status extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitacaoStatusFactory> */
    use HasFactory;
    protected $table = 'solicitacao_status';

    /**
     * Os atributos que podem ser atribuídos em massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome'
    ];
}
