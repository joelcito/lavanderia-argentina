<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preparacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'preparaciones';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'solicitud_id',
        'proceso_id',
        'cantidad',
        'cantidad_liquido',
        'ordenes_trabajo',

        'estado',
        'deleted_at',
    ];

    protected $casts = [
        'ordenes_trabajo' => 'array',
    ];

    public function solicitudPadre()
    {
        return $this->belongsTo('App\Models\Solicitud', 'solicitud_id_padre');
    }
}
