<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;
    
    protected $table = 'enderecos';

    protected $hidden = [
        'deleted_at'
    ];

    protected $fillable = [
        'rua',
        'numero',
        'bairro',
        'cidade',
        'uf',
        'complemento',
        'cep',
        'membro_id'
    ];
}
