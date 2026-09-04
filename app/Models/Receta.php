<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receta extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'recetas';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'tipo_tela_id',
        'color_tela_id',
        'nombre_tela_id',
        'tipo_proceso_id',
        'prelavado_id',
        'focalizado_id',
        'caracteristica_id',
        'nevado_id',
        'nombre',
        'descripcion',

        'estado',
        'deleted_at',
    ];
}
