<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Producto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productos';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'proveedor_id',
        'nombre',
        'tipo',
        'codigo',
        'minimo_stock',

        'estado',
        'deleted_at',
    ];

    public function proveedor()
    {
        return $this->belongsTo('App\Models\Proveedor', 'proveedor_id');
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'producto_id', 'id');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class);
    }

    public function ultimoIngreso()
    {
        return $this->hasOne(Movimiento::class)
            ->where('ingreso', '>', 0)
            ->latestOfMany('id');
    }
}
