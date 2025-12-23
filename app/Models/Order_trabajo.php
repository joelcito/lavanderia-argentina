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
        'nro_ot',
        'estado',
        'deleted_at',
    ];

    public function prenda()
    {
        return $this->belongsTo('App\Models\Prenda', 'prenda_id');
    }

    public function tela()
    {
        return $this->belongsTo('App\Models\Nombre_tela', 'tela_id');
    }

    public function prelavado()
    {
        return $this->belongsTo('App\Models\Prelavado', 'prelavado_id');
    }

    public function nevado()
    {
        return $this->belongsTo('App\Models\Nevado', 'nevado_id');
    }

    public function focalizado()
    {
        return $this->belongsTo('App\Models\Focalizado', 'focalizado_id');
    }

    public function tipoTela()
    {
        return $this->belongsTo('App\Models\Tipo_tela', 'tipo_tela_id');
    }

    public function colorTela()
    {
        return $this->belongsTo('App\Models\Color_tela', 'color_tela_id');
    }

    public function caracteristicaTela()
    {
        return $this->belongsTo('App\Models\Caracteristica', 'color_tela_id');
    }

    public function procesos()
    {
        return $this->hasMany(Proceso::class, 'order_trabajo_id');
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id', 'id');
    }


}

