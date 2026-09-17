<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'facturas';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'cliente_id',
        'usuario_recepciono_id',
        'fecha',
        'nit',
        'razon_social',
        'numero_factura',
        'total',
        'descuento_adicional',
        'descripcion',
        'estado_pago',
        'prioridad',
        'fecha_recepcion',
        'servico_laser',
        'entregado_por',
        'preceso_lavado',

        'estado',
        'deleted_at',
    ];

    public function sucursal()
    {
        return $this->belongsTo('App\Models\Sucursal', 'sucursal_id');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo('App\Models\User', 'usuario_creador_id');
    }

    public function cliente()
    {
        // return $this->belongsTo('App\Models\Cliente', 'cliente_id');
        return $this->belongsTo('App\Models\User', 'usuario_cliente_id');
    }

    public function ordenTrabajos()
    {
        // return $this->hasMany(Order_trabajo::class);
        return $this->hasMany(Order_trabajo::class)->orderBy('tipo', 'desc');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

}
