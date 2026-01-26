<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use App\Models\Factura;
use App\Models\Pago;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Producto;
use App\Models\Order_trabajo;
use App\Models\Tipo_proceso;
use App\Models\Proceso;
use App\Utils\Respuesta;
use App\Models\Maquinaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcesosController extends Controller
{



    public function listado()
    {
        $maquinarias = Maquinaria::all();
        $productos = Producto::all();
        $ordenes = Order_trabajo::with(['factura'])->get();
        // Pasar los datos a la vista
        $facturas = Factura::with('ordenTrabajos')
            ->where(function ($query) {
                $query->where('estado', '!=', 'Anulado') // excluir anuladas
                    ->orWhereNull('estado');          // incluir las que están sin estado (NULL)
            })
            ->get();

        return view('procesos.listado', compact('maquinarias', 'productos', 'ordenes', 'facturas'));
    }



    public function ajaxListado(Request $request)
    {
        if ($request->ajax()) {

            $ots = Order_trabajo::with('procesos')
                ->whereHas('procesos')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'estado' => true,
                'data' => [
                    'listado' => view('procesos.ajaxListado', compact('ots'))->render()
                ]
            ]);
        }

        return response()->json([
            'estado' => false,
            'mensaje' => 'Petición inválida'
        ], 400);
    }




    public function listaProductos()
    {
        $productos = Producto::select('id', 'nombre')->orderBy('nombre')->get();
        return response()->json($productos);
    }

    public function listaTiposProceso()
    {
        $tipos = Tipo_proceso::select('id', 'nombre')->orderBy('nombre')->get();
        return response()->json($tipos);
    }

    public function guardar(Request $request)
    {

        $request->validate([
            'order_trabajo_id' => 'required|exists:order_trabajos,id',
            'producto_id' => 'required|exists:productos,id',
            'tipo_proceso_id' => 'required|exists:tipo_procesos,id',
            'fecha_ingreso' => 'required|date',
        ]);


        $maquinaria = Maquinaria::find($request->maquinaria_id);
        $proceso = Proceso::updateOrCreate(
            ['id' => $request->id],
            [
                'order_trabajo_id' => $request->order_trabajo_id,
                'producto_id' => $request->producto_id,
                'maquinaria_id' => $request->maquinaria_id,
                'tipo_proceso_id' => $request->tipo_proceso_id,
                'fecha_ingreso' => $request->fecha_ingreso,
                'fecha_salida' => $request->fecha_salida,
                'tiempo' => $request->tiempo,
                'temperatura' => $request->temperatura,
                'ph' => $request->ph,
                'rb' => $request->rb,
                'descripcion' => $request->descripcion,
                'estado' => 'EN PROCESO',
            ]
        );

        // Cambiar estado de la maquinaria
        $maquinaria->estado_maquina = 'EN PROCESO';
        $maquinaria->save();

        // Cambiar estado de la OT a TRABAJANDO
        $orderTrabajo = Order_trabajo::find($request->order_trabajo_id);
        if ($orderTrabajo && !in_array($orderTrabajo->estado, ['FINALIZADO', 'ENTREGADO'])) {
            $orderTrabajo->estado = 'TRABAJANDO';
            $orderTrabajo->save();
        }

        return response()->json([
            'estado' => true,
            'mensaje' => 'Proceso registrado correctamente.',
            'data' => $proceso
        ]);
    }

    public function infoMaquinaria(Request $request)
    {
        $maquinaria = Maquinaria::find($request->id);

        if (!$maquinaria) {
            return response()->json([
                'estado_maquina' => 'NO DISPONIBLE',
                'procesos_activos' => 0
            ]);
        }

        $procesosActivos = Proceso::where('maquinaria_id', $maquinaria->id)
            ->whereIn('estado', ['PENDIENTE', 'EN_PROCESO'])
            ->count();

        $estado = ($procesosActivos >= 3)
            ? 'NO DISPONIBLE'
            : 'DISPONIBLE';

        return response()->json([
            'estado_maquina' => $estado,
            'procesos_activos' => $procesosActivos
        ]);
    }




    // public function actualizarEstados()
    // {
    //     // Obtener todos los procesos activos que no estén finalizados
    //     $procesos = Proceso::whereIn('estado', ['PENDIENTE', 'EN_PROCESO'])->get();

    //     foreach ($procesos as $proceso) {
    //         $ahora = now();

    //         // Si la fecha de salida ya pasó, marcar como finalizado
    //         if ($ahora >= $proceso->fecha_salida) {
    //             $proceso->estado = 'FINALIZADO';
    //             $proceso->save();
    //         }
    //     }

    //     return response()->json(['estado' => true]);
    // }


    public function actualizarEstados()
    {
        // Obtener procesos activos
        $procesos = Proceso::whereIn('estado', ['PENDIENTE', 'EN_PROCESO'])->get();

        foreach ($procesos as $proceso) {

            if (!$proceso->fecha_salida) {
                continue;
            }

            if (now() >= $proceso->fecha_salida) {

                // 1️⃣ Finalizar proceso
                $proceso->estado = 'FINALIZADO';
                $proceso->save();

                // 2️⃣ Verificar OT
                $procesosActivosOT = Proceso::where('order_trabajo_id', $proceso->order_trabajo_id)
                    ->whereIn('estado', ['PENDIENTE', 'EN_PROCESO'])
                    ->count();

                if ($procesosActivosOT == 0) {
                    Order_trabajo::where('id', $proceso->order_trabajo_id)
                        ->update(['estado' => 'FINALIZADO']);
                }

                // 3️⃣ Verificar maquinaria
                $procesosActivosMaquina = Proceso::where('maquinaria_id', $proceso->maquinaria_id)
                    ->whereIn('estado', ['PENDIENTE', 'EN_PROCESO'])
                    ->count();

                if ($procesosActivosMaquina == 0) {
                    Maquinaria::where('id', $proceso->maquinaria_id)
                        ->update(['estado_maquina' => 'DISPONIBLE']);
                }
            }
        }

        return response()->json(['estado' => true]);
    }





    public function listaOTs()
    {
        try {
            $ots = Order_trabajo::select('id', 'nro_ot')
                ->whereIn('estado', ['RECEPCIONADO'])
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($ots);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ], 500);
        }
    }


    public function detalleOT($id)
    {
        $procesos = Proceso::with(['producto', 'maquinaria', 'tipoProceso'])
            ->where('order_trabajo_id', $id)
            ->get();

        return response()->json($procesos);
    }

    public function finalizarOT(Request $request)
    {
        $ot = Order_trabajo::find($request->id);

        if (!$ot) {
            return response()->json([
                'estado' => false,
                'mensaje' => 'OT no encontrada'
            ]);
        }

        // Cambiar estado de la OT
        $ot->estado = 'FINALIZADO';
        $ot->save();

        // Finalizar todos los procesos activos de esa OT
        Proceso::where('order_trabajo_id', $ot->id)
            ->whereIn('estado', ['PENDIENTE', 'EN PROCESO'])
            ->update(['estado' => 'FINALIZADO']);

        // Liberar maquinarias involucradas
        $maquinarias = Proceso::where('order_trabajo_id', $ot->id)
            ->pluck('maquinaria_id')
            ->unique();

        foreach ($maquinarias as $maq_id) {
            $activos = Proceso::where('maquinaria_id', $maq_id)
                ->whereIn('estado', ['PENDIENTE', 'EN PROCESO'])
                ->count();

            if ($activos == 0) {
                Maquinaria::where('id', $maq_id)
                    ->update(['estado_maquina' => 'DISPONIBLE']);
            }
        }

        return response()->json([
            'estado' => true,
            'mensaje' => 'Orden de trabajo finalizada correctamente'
        ]);
    }


    public function productosSolicitudesAceptadas(Request $request)
    {
        try {
            $ot_id = $request->query('ot_id');

            if (!$ot_id) {
                return response()->json([
                    'estado' => false,
                    'mensaje' => 'No se proporcionó ot_id',
                    'data' => []
                ]);
            }

            // Verificamos que la OT exista
            $ot = OrderTrabajo::find($ot_id);
            if (!$ot) {
                return response()->json([
                    'estado' => false,
                    'mensaje' => 'OT no encontrada',
                    'data' => []
                ]);
            }

            // Traemos productos aceptados para esa OT
            $productos = Producto::whereHas('solicitudes', function ($query) use ($ot_id) {
                $query->where('order_trabajo_id', $ot_id)
                    ->where('estado', 'ACEPTADO'); // Solo aceptados
            })->get();

            return response()->json([
                'estado' => true,
                'data' => $productos
            ]);

        } catch (\Exception $e) {
            \Log::error('Error productosSolicitudesAceptadas: ' . $e->getMessage());
            return response()->json([
                'estado' => false,
                'mensaje' => 'Error al consultar productos: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    // Productos con stock según movimientos
    public function productosMovimientos()
    {
        $productos = DB::table('productos as p')
            ->join('movimientos as m', 'm.producto_id', '=', 'p.id')
            ->select('p.id', 'p.nombre', DB::raw('SUM(COALESCE(m.ingreso,0) - COALESCE(m.salida,0)) as stock'))
            ->groupBy('p.id', 'p.nombre')
            ->having('stock', '>', 0)
            ->get();

        return response()->json($productos);
    }

    public function productosConStock()
    {
        $productos = DB::table('movimientos')
            ->join('productos', 'productos.id', '=', 'movimientos.producto_id')
            ->select('productos.id', 'productos.nombre', DB::raw('SUM(movimientos.ingreso - movimientos.salida) as stock'))
            ->groupBy('productos.id', 'productos.nombre')
            ->havingRaw('stock > 0')
            ->get();

        return response()->json($productos);
    }

    public function listaOTsPorFactura(Request $request)
    {
        $factura_id = $request->factura_id;

        if (!$factura_id) {
            return response()->json([]);
        }

        $ots = Order_Trabajo::where('factura_id', $factura_id)->get(); // <- aquí puede fallar
        return response()->json($ots);
    }


}
