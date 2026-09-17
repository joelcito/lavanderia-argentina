<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deuda extends Model
{
    use HasFactory;

    protected $table = 'deudas';

    protected $fillable = [

        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'user_id',
        'sucursal_id',

        'concepto',
        'descripcion',

        'monto_total',
        'monto_pagado',
        'saldo_pendiente',

        'estado',
        'fecha',
        'deleted_at',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detalles()
    {
        return $this->hasMany(DeudaDetalle::class, 'deuda_id');
    }
}