<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solicitud extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'solicitudes';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',
        'producto_id',
        'ordenes_trabajo',
        'cantidad',
        'estado',
        'deleted_at'
    ];

    protected $casts = [
        'ordenes_trabajo' => 'array',
    ];

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto', 'producto_id', 'id');
    }

    // public function ordenTrabajo()
    // {
    //     // return $this->belongsTo('App\Models\Order_trabajo', 'orden_trabajo_id');
    //     return $this->belongsTo(Order_trabajo::class, 'orden_trabajo_id');
    // }

    public function usuarioCreador()
    {
        return $this->belongsTo('App\Models\User', 'usuario_creador_id');
    }

}
