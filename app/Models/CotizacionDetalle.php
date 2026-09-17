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
        'producto_id',
        'porcentaje',
        'cantidad',
        'total',

        'estado',
        'deleted_at',
    ];

    public function proceso()
    {
        return $this->belongsTo(Tipo_proceso::class, 'tipo_proceso_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
