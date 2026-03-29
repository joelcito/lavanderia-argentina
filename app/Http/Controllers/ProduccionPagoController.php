<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Pago;
use App\Models\SolicitudDetalleProceso;
use App\Models\Factura;
use App\Models\Prenda;

class ProduccionPagoController extends Controller
{
    public function resumen(Request $request)
    {
        $userId = $request->user_id;
        $tipo = $request->tipo;
        $inicio = $request->inicio;
        $fin = $request->fin;

        $query = SolicitudDetalleProceso::where('usuario_creador_id', $userId)
            ->whereBetween('created_at', [$inicio, $fin]);

        if ($tipo === 'focalizador') {
            $query->where('tipo_proceso', 'FOCALIZADO');
        }

        if ($tipo === 'planchador') {
            $query->where('tipo_proceso', 'PLANCHADO');
        }

        $detalles = $query->get();

        $totalPrendas = 0;
        $total = 0;

        foreach ($detalles as $d) {

            $cantidad = $d->cantidad;
            $totalPrendas += $cantidad;

            if ($tipo === 'focalizador') {

                $factura = Factura::find($d->factura_id);
                $precio = ($factura && $factura->prioridad === 'FERIA') ? 0.30 : 0.50;

                $total += $cantidad * $precio;
            }

            if ($tipo === 'planchador') {

                $prenda = Prenda::find($d->categoria);
                $precio = $prenda->precio_planchado ?? 0;

                $total += $cantidad * $precio;
            }
        }

        // ADELANTOS
        $adelantos = Pago::where('user_id', $userId)
            ->where('tipo_pago', 'adelanto')
            ->whereDate('fecha_inicio', '>=', $inicio)
            ->whereDate('fecha_fin', '<=', $fin)
            ->sum('monto');

        // DESCUENTOS
        $descuentos = Pago::where('user_id', $userId)
            ->where('tipo_pago', 'descuento')
            ->whereDate('fecha_inicio', '>=', $inicio)
            ->whereDate('fecha_fin', '<=', $fin)
            ->sum('monto');

        $ajustes = Pago::where('user_id', $userId)
            ->whereIn('tipo_pago', ['adelanto', 'descuento'])
            ->whereDate('fecha_inicio', '>=', $inicio)
            ->whereDate('fecha_fin', '<=', $fin)
            ->orderBy('fecha', 'desc')
            ->get(['tipo_pago', 'monto', 'descripcion', 'fecha']);

        $pagoRealizado = Pago::where('user_id', $userId)
            ->where('tipo_pago', 'salario')
            ->where('fecha_inicio', $inicio)
            ->where('fecha_fin', $fin)
            ->first();


        // PAGOS
        $pagos = Pago::where('user_id', $userId)
            ->where('tipo_pago', 'salario_produccion')
            ->whereDate('fecha', '<=', $fin)
            ->sum('monto');

        $totalFinal = $total - $adelantos - $descuentos - $pagos;

        return response()->json([
            'total_prendas' => $totalPrendas,
            'total' => $total,

            'adelantos' => $adelantos,
            'descuentos' => $descuentos,
            'pagos' => $pagos,
            'total_final' => $totalFinal,

            'ajustes' => $ajustes,

            'pago_realizado' => $pagoRealizado ? true : false,
            'pago_info' => $pagoRealizado
        ]);
    }

    public function pagar(Request $request)
    {
        if ($request->monto <= 0) {
            return response()->json([
                'error' => 'No hay producción para pagar'
            ], 400);
        }


        $existePago = Pago::where('user_id', $request->user_id)
            ->where('tipo_pago', 'salario')
            ->where(function ($q) use ($request) {
                $q->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
                    ->orWhereBetween('fecha_fin', [$request->fecha_inicio, $request->fecha_fin])
                    ->orWhere(function ($q2) use ($request) {
                        $q2->where('fecha_inicio', '<=', $request->fecha_inicio)
                            ->where('fecha_fin', '>=', $request->fecha_fin);
                    });
            })
            ->exists();

        if ($existePago) {
            return response()->json([
                'error' => 'Ya existe un pago en ese rango de fechas'
            ], 400);
        }

        Pago::create([
            'user_id' => $request->user_id,
            'usuario_creador_id' => auth()->id(),

            'monto' => $request->monto,
            'tipo_pago' => $request->tipo_pago,
            'descripcion' => $request->descripcion ?? 'Pago producción',

            'fecha' => now(),
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin,

            'estado' => 'pagado'
        ]);

        return response()->json(['ok' => true]);
    }
}
