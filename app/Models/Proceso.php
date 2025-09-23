<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proceso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'procesos';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'usuario_lavador_id',
        'order_trabajo_id',
        'producto_id',
        'maquinaria_id',
        'tipo_proceso_id',
        'fecha_ingreso',
        'fecha_salida',
        'cantida',
        'procesoscol',
        'porcentaje',
        'gr_litro',
        'tiempo',
        'temperatura',
        'ph',
        'rb',
        'descripcion',

        'estado',
        'deleted_at',
        
    ];
}
