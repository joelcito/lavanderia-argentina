<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeudaDetalle extends Model
{
    use HasFactory;

    protected $table = 'deuda_detalles';

    protected $fillable = [

        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'deuda_id',
        'user_id',
        'pago_id',

        'tipo_movimiento',
        'monto',
        'descripcion',

        'fecha',
        'estado',
        'deleted_at',
    ];

    public function deuda()
    {
        return $this->belongsTo(Deuda::class, 'deuda_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }
}