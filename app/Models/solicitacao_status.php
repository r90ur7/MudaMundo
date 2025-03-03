<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitacao_status extends Model
{
    /** @use HasFactory<\Database\Factories\SolicitacaoStatusFactory> */
    use HasFactory;
    protected $table = 'solicitacao_status';
}
