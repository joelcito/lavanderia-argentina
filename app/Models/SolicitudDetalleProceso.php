<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SolicitudDetalleProceso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'solicitud_detalle_proceso';

    protected $fillable = [
        'solicitud_id',
        'order_trabajo_id',
        'factura_id',
        'tipo_proceso',
        'categoria',
        'cantidad',
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id'
    ];

    // 🔗 Relaciones
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function ordenTrabajo()
    {
        return $this->belongsTo(Order_Trabajo::class, 'order_trabajo_id');
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function prenda()
    {
        return $this->belongsTo(Prenda::class, 'categoria');

    }


}
