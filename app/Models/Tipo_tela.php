<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tipo_tela extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tipo_telas';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'nombre',

        'estado',
        'deleted_at',
    ];
}
