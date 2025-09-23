<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order_trabajo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_trabajos';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'factura_id',
        'sucursal_id',
        'peso',
        'total',
        'descuento',
        'fecha',
        'cantidad',
        'descripcion',

        'estado',
        'deleted_at',
    ];
}
