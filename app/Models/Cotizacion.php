<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cotizacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cotizaciones';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'cliente_id',
        'mano_obra',
        'servicio_basico',
        'mantenimiento',
        'interes_bancario',
        'costo',
        'precio',
        'utilidad',

        'estado',
        'deleted_at',
    ];
}
