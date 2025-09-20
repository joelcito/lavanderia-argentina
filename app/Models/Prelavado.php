<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prelavado extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'prelavados';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'nombre',

        'estado',
        'deleted_at',
    ];
}
