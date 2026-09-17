<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FacturaRecetaDetalle extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'factura_receta_detalles';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'factura_receta_id',
        'receta_detalle_id',
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

    public function facturaReceta()
    {
        return $this->belongsTo(
            FacturaReceta::class,
            'factura_receta_id'
        );
    }


    public function tipoProceso()
    {
        return $this->belongsTo(
            Tipo_proceso::class,
            'tipo_proceso_id'
        );
    }


    public function producto()
    {
        return $this->belongsTo(
            Producto::class,
            'producto_id'
        );
    }


    public function recetaDetalle()
    {
        return $this->belongsTo(
            RecetaDetalle::class,
            'receta_detalle_id'
        );
    }
}
