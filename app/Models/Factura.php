<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'facturas';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'cliente_id',
        'usuario_recepciono_id',
        'fecha',
        'nit',
        'razon_social',
        'numero_factura',
        'total',
        'descuento_adicional',
        'descripcion',
        'estado_pago',
        'prioridad',
        'fecha_recepcion',
        'servico_laser',
        'entregado_por',
        'preceso_lavado',

        'estado',
        'deleted_at',
    ];
}
