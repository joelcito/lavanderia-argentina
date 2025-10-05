<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Movimiento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'movimientos';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'producto_id',
        'sucursal_id',
        'proceso_id',
        'ingreso',
        'salida',
        'fecha',
        'descripcion',
       

        'estado',
        'deleted_at',
        
    ];

    public function producto(){
        return $this->belongsTo('App\Models\Producto', 'producto_id');
    }

    public function sucursal(){
        return $this->belongsTo('App\Models\Sucursal', 'sucursal_id');
    }
}
