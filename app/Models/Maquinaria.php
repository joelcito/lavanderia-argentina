<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maquinaria extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'maquinarias';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'tipo',
        'numero',
        'descripcion',

        'estado',
        'deleted_at',
    ];
}
