<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'productos';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'proveedor_id',
        'nombre',
        'tipo',
        'codigo',
        'minimo_stock',

        'estado',
        'deleted_at',
    ];
}
