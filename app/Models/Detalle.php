<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detalle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'detalles';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'order_trabajo_id',
        'tipo_tela_id',
        'color_tela_id',
        'caracteristica_id',
        'nombre_tela_id',
        'prelavado_id',
        'focalizado_id',
        'prenda_id',
        'descripcion_adicional',
        'precio',
        'cantidad',
        'descuento',
        'importe',
        'peso',
        'numero_ojales',

        'estado',
        'deleted_at',
    ];


    public function prenda()
    {
        return $this->belongsTo(Prenda::class, 'prenda_id');
    }
    public function nombre_tela()
    {
        return $this->belongsTo(Nombre_tela::class, 'nombre_tela_id');
    }
    public function prelavado()
    {
        return $this->belongsTo(Prelavado::class, 'prelavado_id');
    }
    public function focalizado()
    {
        return $this->belongsTo(Focalizado::class, 'focalizado_id');
    }
}
