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
        'solicitud_id',
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

    public function maquinaria()
    {
        // return $this->belongsTo(Maquinaria::class);
        return $this->belongsTo('App\Models\Maquinaria', 'maquinaria_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function tipoProceso()
    {
        return $this->belongsTo(Tipo_proceso::class, 'tipo_proceso_id');
    }

    public function solicitud()
    {
        return $this->belongsTo('App\Models\Solicitud', 'solicitud_id');
    }
}
