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

    public function tipoTela()
    {
        return $this->belongsTo(
            Tipo_tela::class,
            'tipo_tela_id'
        );
    }

    public function colorTela()
    {
        return $this->belongsTo(
            Color_tela::class,
            'color_tela_id'
        );
    }

    public function nombreTela()
    {
        return $this->belongsTo(
            Nombre_tela::class,
            'nombre_tela_id'
        );
    }

    public function tipoProceso()
    {
        return $this->belongsTo(
            Tipo_proceso::class,
            'tipo_proceso_id'
        );
    }

    public function prelavado()
    {
        return $this->belongsTo(
            Prelavado::class,
            'prelavado_id'
        );
    }

    public function focalizado()
    {
        return $this->belongsTo(
            Focalizado::class,
            'focalizado_id'
        );
    }

    public function caracteristica()
    {
        return $this->belongsTo(
            Caracteristica::class,
            'caracteristica_id'
        );
    }

    public function nevado()
    {
        return $this->belongsTo(
            Nevado::class,
            'nevado_id'
        );
    }

    public function detalles()
    {
        return $this->hasMany(
            RecetaDetalle::class,
            'receta_id'
        )->whereNull('deleted_at');
    }
}
