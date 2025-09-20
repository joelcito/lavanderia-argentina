<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Tipo_proceso extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipo_procesos';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'nombre',

        'estado',
        'deleted_at',
    ];


}
