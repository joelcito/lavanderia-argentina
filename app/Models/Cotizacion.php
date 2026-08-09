<?php

namespace App\Models;

use App\Http\Controllers\CotizacionController;
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

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function prelavado()
    {
        return $this->belongsTo(Prelavado::class, 'prelavado_id');
    }

    public function nevado()
    {
        return $this->belongsTo(Nevado::class, 'nevado_id');
    }

    public function focalizado()
    {
        return $this->belongsTo(Focalizado::class, 'focalizado_id');
    }

    public function detalles()
    {
        return $this->hasMany(CotizacionDetalle::class);
    }
}
