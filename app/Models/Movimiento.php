<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Request;

class Movimiento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'movimientos';

    protected $fillable = [
        'usuario_creador_id',
        'usuario_modificador_id',
        'usuario_eliminador_id',

        'producto_id',
        'sucursal_id',
        'proceso_id',
        'ingreso',
        'salida',
        'fecha',
        'descripcion',


        'estado',
        'deleted_at',

    ];

    protected $casts = [
        'ordenes_trabajo' => 'array',
    ];

    public function producto()
    {
        return $this->belongsTo('App\Models\Producto', 'producto_id');
    }

    public function sucursal()
    {
        return $this->belongsTo('App\Models\Sucursal', 'sucursal_id');
    }

    public function productosConStock()
    {
        // Obtenemos los productos que tengan stock positivo
        $productos = Movimiento::select('producto_id', DB::raw('SUM(ingreso - salida) as stock'))
            ->groupBy('producto_id')
            ->having('stock', '>', 0)
            ->with('producto') // relación con modelo Producto
            ->get()
            ->map(fn($m) => [
                'id' => $m->producto_id,
                'nombre' => $m->producto->nombre ?? 'Sin nombre',
                'stock' => $m->stock
            ]);

        return response()->json($productos);
    }
}
