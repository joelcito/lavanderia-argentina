<?php

namespace App\Http\Controllers;

use DB;
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
            ->whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59']);

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
                $precio = ($factura && $factura->prioridad === 'PARA LA FERIA') ? 0.30 : 0.50;

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
            ->whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59'])
            ->sum(DB::raw('ABS(monto)'));

        // DESCUENTOS
        $descuentos = Pago::where('user_id', $userId)
            ->where('tipo_pago', 'descuento')
            ->whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59'])
            ->sum(DB::raw('ABS(monto)'));

        $ajustes = Pago::where('user_id', $userId)
            ->whereIn('tipo_pago', ['adelanto', 'descuento'])
            ->whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59'])
            ->orderBy('fecha', 'desc')
            ->get(['tipo_pago', 'monto', 'descripcion', 'fecha']);

        $pagoRealizado = Pago::where('user_id', $userId)
            ->where('tipo_pago', 'salario_produccion')
            ->whereBetween('created_at', [$inicio . ' 00:00:00', $fin . ' 23:59:59'])
            ->first();


        // PAGOS
        // $pagos = Pago::where('user_id', $userId)
        //     ->where('tipo_pago', 'salario_produccion')
        //     ->where('fecha_inicio', $inicio)
        //     ->where('fecha_fin', $fin)
        //     ->sum('monto');

        $totalFinal = $total - $adelantos - $descuentos;
        $sinProduccion = $totalPrendas == 0 && $total == 0;

        return response()->json([
            'total_prendas' => $totalPrendas,
            'total' => $total,

            'adelantos' => $adelantos,
            'descuentos' => $descuentos,
            //'pagos' => $pagos,
            'total_final' => $totalFinal,
            'sin_produccion' => $sinProduccion,

            'ajustes' => $ajustes,

            'pago_realizado' => $pagoRealizado ? true : false,
            'pago_info' => $pagoRealizado
        ]);
    }

    public function pagar(Request $request)
    {

        $request->validate([
            'user_id' => 'required|integer',
            'monto' => 'required|numeric|min:0.01',
            'tipo_pago' => 'required|string',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]);




        if ($request->tipo_pago == 'salario_produccion' && $request->monto <= 0) {
            return response()->json([
                'error' => 'No hay producción para pagar'
            ], 400);
        }


        if ($request->tipo_pago == 'salario_produccion') {
            $existePago = Pago::where('user_id', $request->user_id)
                ->where('tipo_pago', 'salario_produccion')
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

            'estado' => 'SALIDA'
        ]);

        return response()->json(['ok' => true]);
    }
}
