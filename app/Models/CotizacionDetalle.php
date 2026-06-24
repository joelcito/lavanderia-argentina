<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CotizacionDetalle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cotizacion_detalles';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'cotizacion_id',
        'tipo_proceso_id',
        'tipo_proceso_id',
        'porcentaje',
        'peso',
        'precio_calculado',

        'estado',
        'deleted_at',
    ];
}
