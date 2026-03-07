<?php

namespace App\Http\Controllers;
use App\Models\Solicitud;
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
use PDF;
use Svg\Tag\Rect;

class ProcesosController extends Controller
{

    public function listado()
    {
        // $maquinarias = Maquinaria::all();
        $productos = Producto::all();
        $ordenes = Order_trabajo::with(['factura'])->get();
        // Pasar los datos a la vista
        $facturas = Factura::with('ordenTrabajos')
            ->where(function ($query) {
                $query->where('estado', '!=', 'Anulado') // excluir anuladas
                    ->orWhereNull('estado');          // incluir las que están sin estado (NULL)
            })
            ->get();

        return view('procesos.listado', compact('productos', 'ordenes', 'facturas'));
    }



    public function ajaxListado(Request $request)
    {
        if ($request->ajax()) {

            $solicitudes = Solicitud::select('ordenes_trabajo')
                ->whereNotNull('ordenes_trabajo')
                ->groupBy('ordenes_trabajo')
                ->get();

            foreach ($solicitudes as $key => $solicitud) {

                // $solicitudBuscada = Solicitud::

                $ordenesTrabajo = $solicitud->ordenes_trabajo;
                $fac = "";
                foreach ($ordenesTrabajo as $key => $ordenTrabajo) {
                    $fac = $fac . " | Fac/Or-Re " . $ordenTrabajo['nro_factura'] . " : [";
                    $ots = $ordenTrabajo['ots'];
                    $arrayOts = array();
                    foreach ($ots as $key => $ot) {
                        $ordenTrabajoBuscado = Order_trabajo::find($ot);
                        if (!in_array($ordenTrabajoBuscado->nro_ot, $arrayOts)) {
                            $fac = $fac . " OT:" . $ordenTrabajoBuscado->nro_ot;
                            array_push($arrayOts, $ordenTrabajoBuscado->nro_ot);
                            if ((count($ots) - 1) == $key)
                                $fac = $fac . "]";
                            else
                                $fac = $fac . " - ";
                        }
                    }
                }

                $json = json_encode($ordenesTrabajo);
                $solicitudesIds = Solicitud::with('producto')->whereRaw(
                    'JSON_CONTAINS(ordenes_trabajo, ?)',
                    [$json]
                )
                    ->pluck('id');

                $proceso = Proceso::with('tipoProceso')
                    ->whereIn('solicitud_id', $solicitudesIds)
                    ->orderByDesc('created_at')
                    ->first();

                // $solicitudArray[$solicitud->id] = $fac;
                if ($proceso) {
                    $solicitudArray[] = [
                        'procesado' => $fac,
                        'crudo' => $ordenesTrabajo,
                        'procesoFinal' => $proceso
                    ];
                }
            }

            // dd($solicitudArray);

            // $ots = Order_trabajo::with('procesos')
            //                     ->whereHas('procesos')
            //                     ->orderBy('created_at', 'desc')
            //                     ->get();

            return response()->json([
                'estado' => true,
                'data' => [
                    'listado' => view('procesos.ajaxListado', compact('solicitudArray'))->render()
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

    // public function guardar(Request $request)
    // {

    //     $request->validate([
    //         'order_trabajo_id' => 'required|exists:order_trabajos,id',
    //         'tipo_proceso_id' => 'required|exists:tipo_procesos,id',
    //         'fecha_ingreso' => 'required|date',
    //     ]);


    //     $maquinaria = Maquinaria::find($request->maquinaria_id);
    //     $proceso = Proceso::updateOrCreate(
    //         ['id' => $request->id],
    //         [
    //             'order_trabajo_id' => $request->order_trabajo_id,
    //             'producto_id' => $request->producto_id,
    //             'maquinaria_id' => $request->maquinaria_id,
    //             'tipo_proceso_id' => $request->tipo_proceso_id,
    //             'fecha_ingreso' => $request->fecha_ingreso,
    //             'fecha_salida' => $request->fecha_salida,
    //             'tiempo' => $request->tiempo,
    //             'temperatura' => $request->temperatura,
    //             'ph' => $request->ph,
    //             'rb' => $request->rb,
    //             'descripcion' => $request->descripcion,
    //             'estado' => 'EN PROCESO',
    //         ]
    //     );

    //     // Cambiar estado de la maquinaria
    //     $maquinaria->estado_maquina = 'EN PROCESO';
    //     $maquinaria->save();


    //     // Cambiar estado de la OT a TRABAJANDO
    //     $orderTrabajo = Order_trabajo::find($request->order_trabajo_id);
    //     if ($orderTrabajo && !in_array($orderTrabajo->estado, ['FINALIZADO', 'ENTREGADO'])) {
    //         $orderTrabajo->estado = 'TRABAJANDO';
    //         $orderTrabajo->save();
    //     }

    //     return response()->json([
    //         'estado' => true,
    //         'mensaje' => 'Proceso registrado correctamente.',
    //         'data' => $proceso
    //     ]);
    // }

    public function guardar(Request $request)
    {
        $request->validate([
            'order_trabajo_id' => 'required|exists:order_trabajos,id',
            'tipo_proceso_id' => 'required|exists:tipo_procesos,id',
            'fecha_ingreso' => 'required|date',
            'producto_id' => 'nullable|exists:productos,id',
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
        if ($maquinaria) {
            $maquinaria->estado_maquina = 'EN PROCESO';
            $maquinaria->save();
        }

        // Cambiar estado de la OT a TRABAJANDO si aplica
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

    public function guardarListado(Request $request)
    {
        // dd($request->all());

        $procesos = $request->input('procesos', []); // array de procesos del listado

        if (empty($procesos)) {
            return response()->json([
                'estado' => false,
                'mensaje' => 'No se encontraron procesos para guardar.'
            ]);
        }

        $resultados = [];
        $ocurrioError = false;
        $arrayErrores = [];

        foreach ($procesos as $item) {
            // Validar campos mínimos por cada proceso
            $validator = \Validator::make($item, [
                // 'order_trabajo_id' => 'required|exists:order_trabajos,id',
                'tipo_proceso_id' => 'required|exists:tipo_procesos,id',
                'fecha_ingreso' => 'required|date',
                'producto_id' => 'nullable|exists:productos,id',
                'maquinaria_id' => 'required|exists:maquinarias,id',
            ]);

            if ($validator->fails()) {
                // Omitir este registro y continuar con los demás
                $arrayErrores[] = [
                    'fila' => $item,
                    'errores' => $validator->errors()->all()
                ];
                $ocurrioError = true;
                continue;
            }

            $maquinaria = Maquinaria::find($item['maquinaria_id']);

            // SACAMOS LA SOLICITUD
            $solicitud = Solicitud::find($item['ordenes_trabajos_solicitudes_aprobados']);
            $ordenesTrabajo = $solicitud->ordenes_trabajo;
            foreach ($ordenesTrabajo as $key => $ordeTrabajo) {
                $ots = $ordeTrabajo['ots'];
                foreach ($ots as $key => $ot) {
                    $proceso = Proceso::create([
                        // 'order_trabajo_id' => $ot,
                        'producto_id' => $item['producto_id'] ?? null,
                        'solicitud_id' => $solicitud->id,
                        'maquinaria_id' => $item['maquinaria_id'],
                        'tipo_proceso_id' => $item['tipo_proceso_id'],
                        'fecha_ingreso' => $item['fecha_ingreso'],
                        'fecha_salida' => $item['fecha_salida'] ?? null,
                        'tiempo' => $item['tiempo'] ?? null,
                        'temperatura' => $item['temperatura'] ?? null,
                        'ph' => $item['ph'] ?? null,
                        'rb' => $item['rb'] ?? null,
                        'descripcion' => $item['descripcion'] ?? null,
                        'estado' => 'TRABAJANDO',
                        'order_trabajo_id' => $ot,
                    ]);

                    // Cambiar estado de la OT a TRABAJANDO si aplica
                    $orderTrabajo = Order_trabajo::find($ot);
                    if ($orderTrabajo && !in_array($orderTrabajo->estado, ['FINALIZADO', 'ENTREGADO'])) {
                        $orderTrabajo->estado = 'TRABAJANDO';
                        $orderTrabajo->save();
                    }
                }
            }

            $solicitud->estado = "UTILIZADO";
            $solicitud->save();

            // Cambiar estado de la maquinaria a EN PROCESO
            if ($maquinaria) {
                $maquinaria->estado_maquina = 'EN PROCESO';
                $maquinaria->save();
            }

            $resultados[] = $proceso;
        }

        if ($ocurrioError) {
            return response()->json([
                'estado' => false,
                'mensaje' => 'Algunos registros no se actualizaron.',
                'data' => $arrayErrores
            ]);
        } else {
            return response()->json([
                'estado' => true,
                'mensaje' => 'Listado de procesos guardado correctamente.',
                'data' => $resultados
            ]);
        }

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
            $ot = Order_Trabajo::find($ot_id);
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
                    ->where('estado', 'APROBADO'); // Solo aceptados
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


    public function otsPorFactura(Request $request)
    {
        $factura_id = $request->query('factura_id'); // nota: query string

        if (!$factura_id)
            return response()->json([]);

        $ots = Solicitud::where('factura_id', $factura_id)
            ->where('estado', 'APROBADO') // solo aprobados
            ->select('orden_trabajo_id')
            ->distinct()
            ->pluck('orden_trabajo_id')
            ->toArray();

        $data = Order_trabajo::whereIn('id', $ots)
            ->get()
            ->map(function ($ot) {
                return [
                    'id' => $ot->id,
                    'nro_ot' => $ot->nro_ot,
                    'peso_total' => $ot->peso_total ?? 0,
                ];
            });

        return response()->json($data);
    }

    public function productosAprobadosPorOT($ot_id)
    {
        $solicitudes = Solicitud::whereJsonContains('orden_trabajo_id', $ot_id)
            ->where('estado', 'APROBADO')
            ->get();

        $productos = collect();

        foreach ($solicitudes as $solicitud) {
            $ids = json_decode($solicitud->producto_id);
            if (is_array($ids)) {
                $productos->push(...$ids);
            }
        }

        $productos = $productos->unique()->values();

        $productosData = Producto::whereIn('id', $productos)->get(['id', 'nombre']);

        return response()->json($productosData);
    }

    //planchado y focalizado

    public function obtenerOT($id)
    {
        return Order_Trabajo::select('id', 'cantidad')
            ->where('id', $id)
            ->firstOrFail();
    }


    public function guardarProcesoOT(Request $request)
    {
        try {
            $request->validate([
                'ot_id' => 'required|exists:order_trabajos,id',
                'tipo' => 'required|in:focalizado,planchado',
                'cantidad' => 'required|numeric|min:0'
            ]);

            $ot = Order_Trabajo::findOrFail($request->ot_id);


            if (strtoupper($ot->estado) === 'FINALIZADO') {
                return response()->json([
                    'estado' => false,
                    'mensaje' => 'No se puede modificar, la OT ya está finalizada.'
                ], 422);
            }

            if ($request->tipo === 'focalizado') {
                $ot->cantidad_focalizado = (int) $request->cantidad;
            } else {
                $ot->cantidad_planchado = (int) $request->cantidad;
            }

            $ot->save();

            return response()->json([
                'estado' => true,
                'mensaje' => 'Cantidad guardada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'estado' => false,
                'mensaje' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verProcesoEnMarchaMaquina(Request $request)
    {

        if ($request->ajax()) {

            $maquina_id = $request->input('maquina');

            $procesos = Proceso::select(
                'procesos.maquinaria_id',
                'procesos.solicitud_id',
                'procesos.producto_id',
                'procesos.tipo_proceso_id',
                'procesos.fecha_ingreso',
                'procesos.fecha_salida',
                'procesos.tiempo',
                'procesos.temperatura',
                'procesos.ph',
                'procesos.rb',
                'procesos.descripcion',
                'procesos.estado',
            )
                ->where('procesos.maquinaria_id', $maquina_id)
                ->where('procesos.estado', 'TRABAJANDO')
                ->groupBy(
                    'procesos.maquinaria_id',
                    'procesos.solicitud_id',
                    'procesos.producto_id',
                    'procesos.tipo_proceso_id',
                    'procesos.fecha_ingreso',
                    'procesos.fecha_salida',
                    'procesos.tiempo',
                    'procesos.temperatura',
                    'procesos.ph',
                    'procesos.rb',
                    'procesos.descripcion',
                    'procesos.estado',
                )
                ->get();

            // dd($solicitudes);

            $fac = "";
            $solicitudArray = [];

            foreach ($procesos as $key => $proceso) {

                // dd($solicitud['id'] );

                $soliBuscado = Solicitud::find($proceso->solicitud_id);
                $ordenesTrabajo = $soliBuscado->ordenes_trabajo;
                $fac = "";
                foreach ($ordenesTrabajo as $key => $ordenTrabajo) {
                    $fac = $fac . " | Fac/Or-Re " . $ordenTrabajo['nro_factura'] . " : [";
                    $ots = $ordenTrabajo['ots'];
                    $arrayOts = array();
                    foreach ($ots as $key => $ot) {
                        $ordenTrabajoBuscado = Order_trabajo::find($ot);
                        if (!in_array($ordenTrabajoBuscado->nro_ot, $arrayOts)) {
                            $fac = $fac . " OT:" . $ordenTrabajoBuscado->nro_ot;
                            array_push($arrayOts, $ordenTrabajoBuscado->nro_ot);
                            if ((count($ots) - 1) == $key)
                                $fac = $fac . "]";
                            else
                                $fac = $fac . " - ";
                        }
                    }
                }

                $solicitudArray[$proceso->solicitud_id] = $fac;
            }

            // dd(
            //     $solicitudArray,
            //     $procesos,
            //     count($procesos)
            // );

            $valores = [
                'procesos' => $procesos,
                'fac' => $solicitudArray,
                'listado' => view('procesos.verProcesoEnMarchaMaquina')->with(compact('procesos', 'fac'))->render(),
                'dato' => $fac
            ];

            // dd($valores);

            $data = Respuesta::success($valores, "SE PROCESO CON EXITO");

        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;

    }

    public function finalizarProceso(Request $request)
    {

        if ($request->ajax()) {

            $maquina_id = $request->input('maquina');

            $procesos = Proceso::where('maquinaria_id', $maquina_id)
                ->where('estado', 'TRABAJANDO')
                ->get();

            if ($procesos) {
                foreach ($procesos as $key => $proceso) {
                    $proceso->estado = 'EN PROCESO';
                    $proceso->save();
                }
                // AHORA LA MAQUINA
                $maquina = Maquinaria::find($maquina_id);
                $maquina->estado_maquina = 'DISPONIBLE';
                $maquina->save();

                $data = Respuesta::success(null, "SE FINALIZO CON EXITO");
            } else {
                $data = Respuesta::error(null, "NO SE ENCONTRO PROCESOS EN LA MAQUINA");
            }
        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;
    }

    public function buscarSolicitudesProductoSoloAgua(Request $request)
    {
        if ($request->ajax()) {

            // dd($request->all());

            $solicitudes = Solicitud::where('estado', 'UTILIZADO')
                ->get();

            $fac = "";
            $solicitudArray = [];

            foreach ($solicitudes as $key => $solicitud) {
                $ordenesTrabajo = $solicitud->ordenes_trabajo;
                $fac = "";
                foreach ($ordenesTrabajo as $key => $ordenTrabajo) {
                    $fac = $fac . " | Fac/Or-Re " . $ordenTrabajo['nro_factura'] . " : [";
                    $ots = $ordenTrabajo['ots'];
                    $arrayOts = array();
                    foreach ($ots as $key => $ot) {
                        $ordenTrabajoBuscado = Order_trabajo::find($ot);
                        if (!in_array($ordenTrabajoBuscado->nro_ot, $arrayOts)) {
                            $fac = $fac . " OT:" . $ordenTrabajoBuscado->nro_ot;
                            array_push($arrayOts, $ordenTrabajoBuscado->nro_ot);
                            if ((count($ots) - 1) == $key)
                                $fac = $fac . "]";
                            else
                                $fac = $fac . " - ";
                        }
                    }
                }

                $solicitudArray[$solicitud->id] = $fac;
            }

            $valores = [
                'solicitudes' => $solicitudes,
                'fac' => $solicitudArray
            ];

            $data = Respuesta::success($valores, "Datos obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;
    }

    public function ajaxListadoMaquinas(Request $request)
    {

        if ($request->ajax()) {
            $maquinarias = Maquinaria::all();
            $valores = [
                'listado' => view('procesos.ajaxListadoMaquinas')->with(compact('maquinarias'))->render()
            ];
            $data = Respuesta::success($valores, "Datos obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;
    }

    public function sacarSolicitudesAgrupados(Request $request)
    {

        if ($request->ajax()) {

            $solicitudes = Solicitud::where('estado', 'UTILIZADO')
                ->get();

            $fac = "";
            $solicitudArray = [];

            foreach ($solicitudes as $key => $solicitud) {
                $ordenesTrabajo = $solicitud->ordenes_trabajo;
                $fac = "";
                foreach ($ordenesTrabajo as $key => $ordenTrabajo) {
                    $fac = $fac . " | Fac/Or-Re " . $ordenTrabajo['nro_factura'] . " : [";
                    $ots = $ordenTrabajo['ots'];
                    $arrayOts = array();
                    foreach ($ots as $key => $ot) {
                        $ordenTrabajoBuscado = Order_trabajo::find($ot);
                        if (!in_array($ordenTrabajoBuscado->nro_ot, $arrayOts)) {
                            $fac = $fac . " OT:" . $ordenTrabajoBuscado->nro_ot;
                            array_push($arrayOts, $ordenTrabajoBuscado->nro_ot);
                            if ((count($ots) - 1) == $key)
                                $fac = $fac . "]";
                            else
                                $fac = $fac . " - ";
                        }
                    }
                }

                $solicitudArray[$solicitud->id] = $fac;
            }

            $valores = [
                'solicitudes' => $solicitudes,
                'fac' => $solicitudArray
            ];

            $data = Respuesta::success($valores, "Datos obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;
    }

    public function sacarPesoTotalSolicitudAgrupado(Request $request)
    {

        if ($request->ajax()) {

            // dd($request->all());

            $solitud_id = $request->input('dato');
            $solicitud = Solicitud::find($solitud_id);
            $ordenes_trabajo = $solicitud->ordenes_trabajo;
            $sumaTotalPesos = 0;

            foreach ($ordenes_trabajo as $key => $ordenTrabajo) {
                $ots = $ordenTrabajo['ots'];
                foreach ($ots as $key => $ot) {
                    $otBuscado = Order_trabajo::find($ot);
                    $sumaTotalPesos += $otBuscado->peso;
                }
            }

            $valores = [
                'peso_total' => $sumaTotalPesos,
            ];

            $data = Respuesta::success($valores, "Datos obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;

    }

    public function GuardarSolicitudAgrupado(Request $request)
    {

        if ($request->ajax()) {

            // dd($request->all());

            $solicitudes = $request->input('solicitudes');
            $usuario = Auth::user();

            foreach ($solicitudes as $key => $solicitud) {

                $solicitudBuscado = Solicitud::find($solicitud['solicitud']);

                $solicitudNew = new Solicitud();
                $solicitudNew->usuario_creador_id = $usuario->id;
                $solicitudNew->producto_id = $solicitud['producto_id'];
                $solicitudNew->ordenes_trabajo = $solicitudBuscado->ordenes_trabajo;
                $solicitudNew->cantidad = $solicitud['cantidad'];
                $solicitudNew->porcentaje = $solicitud['porcentaje'];
                $solicitudNew->estado = $solicitud['estado'];
                $solicitudNew->save();

                $data = Respuesta::success(null, "Datos obtenidos correctamente");
            }

        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;

    }

    public function guardaEdicionProceso(Request $request)
    {

        if ($request->ajax()) {

            // dd($request->all());
            $maquina_id_proceso = $request->input('maquina_id_proceso');
            $producto_id_proceso = $request->input('producto_id_proceso');
            $tipo_proceso_id_proceso = $request->input('tipo_proceso_id_proceso');
            $fecha_ini_proceso = $request->input('fecha_ini_proceso');
            $tiempo_proceso = $request->input('tiempo_proceso');
            $fecha_fin_proceso = $request->input('fecha_fin_proceso');

            $procesos = Proceso::where('maquinaria_id', $maquina_id_proceso)
                ->where('estado', 'TRABAJANDO')
                ->where('tipo_proceso_id', $tipo_proceso_id_proceso)
                ->get();

            foreach ($procesos as $key => $proceso) {
                $proceso->tiempo = $tiempo_proceso;
                $proceso->fecha_salida = $fecha_fin_proceso;
                $proceso->save();
            }

            $data = Respuesta::success(null, "Datos editados correctamente");

        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;

    }

    public function agregarProductoAlProceso(Request $request)
    {

        if ($request->ajax()) {

            $maquinaria_id = $request->input('maquina');
            $tipo_proceso_id = $request->input('tipo_proceso');

            $proceso = Proceso::where('maquinaria_id', $maquinaria_id)
                ->where('tipo_proceso_id', $tipo_proceso_id)
                ->where('estado', 'TRABAJANDO')
                ->first();

            if ($proceso) {
                $solicitud = $proceso->solicitud;

                // $solicitudes = Solicitud::where('ordenes_trabajo', $solicitud->ordenes_trabajo)
                //                         ->where('estado', 'APROBADO')
                //                         ->get();

                $json = json_encode($solicitud->ordenes_trabajo);

                $solicitudes = Solicitud::with('producto')->whereRaw(
                    'JSON_CONTAINS(ordenes_trabajo, ?)',
                    [$json]
                )
                    // ->where('estado', 'APROBADO')
                    ->get();

                // dd($solicitudes);

                $valores = [
                    'solicitudes' => $solicitudes
                ];

                $data = Respuesta::success($valores, "Datos editados correctamente");

            } else {
                $data = Respuesta::error(null, "No existe datos");
            }
        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;
    }

    public function agregarProductoProcesoNuevo(Request $request)
    {

        if ($request->ajax()) {

            // dd($request->all());
            $solicitud_id_agregacion_proceso = $request->input('solicitud_id_agregacion_proceso');
            $fecha_ini_agregacion_proceso = $request->input('fecha_ini_agregacion_proceso');
            $temperatura_agregacion_proceso = $request->input('temperatura_agregacion_proceso');
            $ph_agregacion_proceso = $request->input('ph_agregacion_proceso');
            $rb_agregacion_proceso = $request->input('rb_agregacion_proceso');
            $descripcion_agregacion_proceso = $request->input('descripcion_agregacion_proceso');
            $maquina_idagregacion_proceso = $request->input('maquina_idagregacion_proceso');
            $tipo_proceso_idagregacion_proceso = $request->input('tipo_proceso_idagregacion_proceso');

            $solicitud = Solicitud::find($solicitud_id_agregacion_proceso);
            $ordenesTrabajo = $solicitud->ordenes_trabajo;

            $procesoBuscado = Proceso::where('maquinaria_id', $maquina_idagregacion_proceso)
                ->where('tipo_proceso_id', $tipo_proceso_idagregacion_proceso)
                ->where('estado', 'TRABAJANDO')
                ->first();

            foreach ($ordenesTrabajo as $key => $ordenTrabajo) {
                $ots = $ordenTrabajo['ots'];
                foreach ($ots as $key => $ot) {

                    $proceso = Proceso::create([
                        // 'order_trabajo_id' => $ot,
                        'producto_id' => $solicitud->producto_id,
                        'solicitud_id' => $solicitud->id,
                        'maquinaria_id' => $maquina_idagregacion_proceso,
                        'tipo_proceso_id' => $tipo_proceso_idagregacion_proceso,
                        'fecha_ingreso' => $fecha_ini_agregacion_proceso,
                        'fecha_salida' => $procesoBuscado->fecha_salida,
                        'tiempo' => $procesoBuscado->tiempo,
                        'temperatura' => $temperatura_agregacion_proceso,
                        'ph' => $ph_agregacion_proceso,
                        'rb' => $rb_agregacion_proceso,
                        'descripcion' => $descripcion_agregacion_proceso,
                        'estado' => 'TRABAJANDO',
                        'order_trabajo_id' => $ot,
                    ]);

                    $orderTrabajo = Order_trabajo::find($ot);
                    if ($orderTrabajo && !in_array($orderTrabajo->estado, ['FINALIZADO', 'ENTREGADO'])) {
                        $orderTrabajo->estado = 'TRABAJANDO';
                        $orderTrabajo->save();
                    }
                }
            }

            $solicitud->estado = "UTILIZADO";
            $solicitud->save();

            $data = Respuesta::success(null, "Datos editados correctamente");

        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;

    }

    public function generaPDFHistorialProceso(Request $request)
    {
        $tipo = $request->input('tipo');

        foreach ($tipo as &$item) {
            $item['factura_id'] = (int) $item['factura_id'];
            $item['nro_factura'] = (int) $item['nro_factura'];
            $item['ots'] = array_map('intval', $item['ots']);
        }

        $query = Solicitud::query();

        $query->where(function ($q) use ($tipo) {

            foreach ($tipo as $item) {

                $q->orWhere(function ($sub) use ($item) {

                    // Buscar objeto que tenga ese factura_id
                    $sub->whereRaw(
                        "JSON_CONTAINS(ordenes_trabajo, ?)",
                        [json_encode(['factura_id' => $item['factura_id']])]
                    );

                    // Buscar que el objeto contenga exactamente esas OTs
                    $sub->whereRaw(
                        "JSON_CONTAINS(ordenes_trabajo, ?)",
                        [
                            json_encode([
                                'factura_id' => $item['factura_id'],
                                'ots' => $item['ots']
                            ])
                        ]
                    );

                });

            }

        });

        $solicitudes = $query->pluck('id');

        $procesos = Proceso::select(
            'procesos.producto_id',
            'procesos.maquinaria_id',
            'procesos.tipo_proceso_id',
            'procesos.fecha_ingreso',
            'procesos.fecha_salida',
            'procesos.tiempo',
            'procesos.temperatura',
            'procesos.ph',
            'procesos.rb',
            'procesos.descripcion',
            'solicitudes.cantidad',
            'solicitudes.porcentaje'
        )
            ->join('solicitudes', 'solicitudes.id', '=', 'procesos.solicitud_id')
            ->with('maquinaria')
            ->with('producto')
            ->with('tipoProceso')
            ->whereIn('solicitud_id', $solicitudes)
            ->groupBy(
                'procesos.producto_id',
                'procesos.maquinaria_id',
                'procesos.tipo_proceso_id',
                'procesos.fecha_ingreso',
                'procesos.fecha_salida',
                'procesos.tiempo',
                'procesos.temperatura',
                'procesos.ph',
                'procesos.rb',
                'procesos.descripcion',
                'solicitudes.cantidad',
                'solicitudes.porcentaje'
            )
            ->get();

        // dd($procesos, $solicitudes);
        $usuario = Auth::user();

        // Generar PDF
        $pdf = PDF::loadView('procesos.pdf.historialProcesos', [
            'procesos' => $procesos,
            'tipo' => $tipo,
            'usuario' => $usuario
        ]);

        return $pdf->stream('historial_proceso.pdf');
    }

    public function enviarProcesoFocalizado(Request $request)
    {
        if ($request->ajax()) {

            // dd($request->all());
            $datos = $request->input('d');
            $ots = $datos[0]['ots'];
            $usuario = Auth::user();

            foreach ($datos as &$item) {
                $item['factura_id'] = (int) $item['factura_id'];
                $item['nro_factura'] = (int) $item['nro_factura'];
                $item['ots'] = array_map('intval', $item['ots']);
            }

            // CREAMOS LA SOLICITUD
            $solicitud = new Solicitud();
            $solicitud->usuario_creador_id = $usuario->id;
            $solicitud->ordenes_trabajo = $datos;
            $solicitud->cantidad = 0;
            $solicitud->porcentaje = 0;
            $solicitud->estado = "EN PROCESO";
            $solicitud->save();

            foreach ($ots as $key => $ot) {
                $proceso = new Proceso();
                $proceso->usuario_creador_id = $usuario->id;
                $proceso->order_trabajo_id = $ot;
                $proceso->tipo_proceso_id = 4;               //FOCALIZADO
                $proceso->solicitud_id = $solicitud->id;
                $proceso->estado = "EN PROCESO";
                $proceso->save();
            }

            $data = Respuesta::success(null, "Carga enviado a FOCALIZADO");

        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;
    }

    public function focalizadoListado(Request $request)
    {

        return view('procesos.focalizadoListado');

    }

    public function ajaxListadoSolicitudesFocalizado(Request $request)
    {

        if ($request->ajax()) {

            $solicitudes = Solicitud::whereNull('producto_id')
                ->get();


            $solicitudArray = [];

            foreach ($solicitudes as $key => $solicitud) {

                $ordenesTrabajo = $solicitud->ordenes_trabajo;
                $fac = "";

                foreach ($ordenesTrabajo as $key => $ordenTrabajo) {
                    $fac = $fac . " | Fac/Or-Re " . $ordenTrabajo['nro_factura'] . " : [";
                    $ots = $ordenTrabajo['ots'];
                    $arrayOts = array();
                    foreach ($ots as $key => $ot) {
                        $ordenTrabajoBuscado = Order_trabajo::find($ot);
                        if (!in_array($ordenTrabajoBuscado->nro_ot, $arrayOts)) {
                            $fac = $fac . " OT:" . $ordenTrabajoBuscado->nro_ot;
                            array_push($arrayOts, $ordenTrabajoBuscado->nro_ot);
                            if ((count($ots) - 1) == $key)
                                $fac = $fac . "]";
                            else
                                $fac = $fac . " - ";
                        }
                    }
                }
                $solicitudArray[$solicitud->id] = $fac;
            }

            // dd($solicitudArray);

            $valores = [
                'listado' => view('procesos.ajaxListadoSolicitudesFocalizado')->with(compact('solicitudArray'))->render()
            ];

            $data = Respuesta::success($valores, "Se proceso con EXITO");
        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;

    }

    public function focalizadoListadoSolicitud()
    {

        $productos = Producto::all();

        return view('procesos.focalizadoListadoSolicitud')->with(compact('productos'));

    }

    public function guardarSolicitudFocalizado(Request $request)
    {

        if ($request->ajax()) {

            // dd($request->all());
            $id = $request->input('id');
            $producto_id = $request->input('producto_id');
            $cantidad = $request->input('cantidad');
            $porcentaje = $request->input('porcentaje');
            $usuario = Auth::user();

            $solicitud = new Solicitud();
            $solicitud->usuario_creador_id = $usuario->id;
            $solicitud->producto_id = $producto_id;
            $solicitud->cantidad = $cantidad;
            $solicitud->porcentaje = $porcentaje;
            $solicitud->estado = "EN PROCESO";
            $solicitud->save();

            $data = Respuesta::success(null, "SE CREO CON EXITO");

        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;
    }

    public function ajaxListadoSolicitudFocalizado(Request $request)
    {

        if ($request->ajax()) {

            $solicitudes = Solicitud::whereNull('ordenes_trabajo')
                ->get();

            $valores = [
                'listado' => view('procesos.ajaxListadoSolicitudFocalizado')->with(compact('solicitudes'))->render()
            ];

            $data = Respuesta::success($valores, "Se proceso con EXITO");

        } else {
            $data = Respuesta::error(null, "No existe");
        }
        return $data;

    }

}
