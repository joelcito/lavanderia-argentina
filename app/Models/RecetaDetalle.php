<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecetaDetalle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'receta_detalles';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'receta_id',
        'tipo_proceso_id',
        'producto_id',
        'orden_proceso',
        'orden_producto',
        'porcentaje',
        'cantidad',
        'total',
        'tiempo',
        'temperatura',
        'ph',
        'rb',
        'descripcion',

        'estado',
        'deleted_at',
    ];
}
