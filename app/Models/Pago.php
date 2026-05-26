<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pagos';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'factura_id',
        'sucursal_id',

        'monto',
        'cambio',
        'fecha',
        'descripcion',
        'tipo_pago',

        'user_id',
        'fecha_inicio',
        'fecha_fin',

        'pago_diario_usado',
        'horas_base_usado',

        'total_horas',
        'total_minutos',

        'monto_calculado',
        'total_descuentos',

        'estado',
        'deleted_at',
    ];

    public function usuario()
    {
        return $this->belongsTo('App\Models\User', 'usuario_creador_id');
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Models\Sucursal', 'sucursal_id');
    }

    public function factura()
    {
        return $this->belongsTo('App\Models\Factura', 'factura_id');
    }

    public function subCategoria()
    {
        return $this->belongsTo('App\Models\SubCategoria', 'sub_categoria_id');
    }
}
