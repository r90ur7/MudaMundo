<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MudaStatus extends Model
{
    use HasFactory;

    protected $table = 'muda_status';

    protected $fillable = ['nome'];

    public $timestamps = true;
}
