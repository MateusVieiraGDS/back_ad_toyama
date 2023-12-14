<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembroSituacao extends Model
{
    use HasFactory;
    
    protected $table = 'situacoes';

    protected $hidden = [
        'deleted_at'
    ];

    protected $fillable = [
        'nome',
        'description'
    ];
}
