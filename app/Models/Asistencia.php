<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asistencia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asistencias';

    protected $fillable = [

        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',
        'user_id',
        'fecha',
        'hora_entrada',
        'hora_salida',

    ];

    protected $dates = [
        'deleted_at'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'usuario_creador_id');
    }

    public function usuarioModificador()
    {
        return $this->belongsTo(User::class, 'usuario_modificador_id');
    }

    public function usuarioEliminador()
    {
        return $this->belongsTo(User::class, 'usuario_eliminador_id');
    }
}
