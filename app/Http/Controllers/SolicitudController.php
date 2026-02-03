<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Solicitud;
use App\Models\Order_trabajo;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class SolicitudController extends Controller
{
    public function listado()
    {
        $facturas = Factura::with('ordenTrabajos')->get(); // ← aquí es clave
        $ordenes = Order_Trabajo::all();
        $solicitudes = Solicitud::with(['producto', 'usuarioCreador'])
            ->orderBy('created_at', 'desc')
            ->get();


        return view('solicitudes.listado', compact('facturas', 'ordenes', 'solicitudes'));
    }


    public function listaOTs()
    {
        $ots = DB::table('orden_trabajos')->select('id', 'numero_ot')->get();
        return response()->json($ots);
    }


    public function ajaxListado(Request $request)
    {
        if (!$request->ajax()) {
            return Respuesta::error(null, "Petición no válida");
        }

        $solicitudes = Solicitud::with('usuarioCreador')
            ->whereNull('deleted_at')
            ->get();

        $facturasSolicitadas = collect();

        foreach ($solicitudes as $solicitud) {

            if (!is_array($solicitud->ordenes_trabajo)) {
                continue;
            }

            foreach ($solicitud->ordenes_trabajo as $item) {

                // 🔒 PROTECCIÓN CONTRA DATOS VIEJOS
                if (!is_array($item) || !isset($item['factura_id'])) {
                    continue;
                }

                $facturasSolicitadas->push((object) [
                    'factura_id' => $item['factura_id'],
                    'numero_factura' => $item['nro_factura'] ?? '',
                    'usuarioCreador' => $solicitud->usuarioCreador,
                    'ots' => $item['ots'] ?? [],
                ]);
            }
        }

        return Respuesta::success([
            'listado' => view(
                'solicitudes.ajaxListado',
                compact('facturasSolicitadas')
            )->render()
        ], "Datos obtenidos correctamente");
    }




    // public function ajaxListado(Request $request)
    // {

    //     if ($request->ajax()) {

    //         $facturasSolicitadas = Solicitud::join('order_trabajos as ot', function ($join) {
    //             $join->whereRaw('JSON_CONTAINS(solicitudes.ordenes_trabajo, CAST(ot.id AS JSON))');
    //         })
    //             ->join('facturas as f', 'f.id', '=', 'ot.factura_id')
    //             ->select('ot.factura_id', 'solicitudes.usuario_creador_id', 'f.numero_factura')
    //             ->groupBy('ot.factura_id', 'solicitudes.usuario_creador_id', 'f.numero_factura')
    //             ->get();

    //         $valores = [
    //             'listado' => view('solicitudes.ajaxListado', compact('facturasSolicitadas'))->render()
    //         ];

    //         $data = Respuesta::success($valores, "Datos obtenidos correctamente");

    //     } else {

    //         $data = Respuesta::error(null, "Error al obtener los datos");
    //     }
    //     return $data;



    //     // $solicitudes = Solicitud::with(['producto', 'usuarioCreador'])->get();

    //     // // Aplanamos todos los arrays de OT y agrupamos por OT individual
    //     // $ots = $solicitudes->flatMap(fn($s) => collect($s->ordenes_trabajo))
    //     //     ->groupBy(fn($id) => $id);

    //     // $html = view('solicitudes.ajaxListado', compact('ots'))->render();

    //     // return response()->json([
    //     //     'estado' => true,
    //     //     'data' => ['listado' => $html]
    //     // ]);
    // }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {
            foreach ($request->solicitudes as $item) {

                $solicitud = new Solicitud();
                $solicitud->usuario_creador_id = auth()->id();
                $solicitud->producto_id = $item['producto_id'];

                $facturas = $item['facturas'];
                foreach ($facturas as &$f) {
                    $f['factura_id'] = (int) $f['factura_id'];
                    $f['nro_factura'] = (int) $f['nro_factura'];
                    $f['ots'] = array_map('intval', $f['ots']);
                }
                $solicitud->ordenes_trabajo = $facturas;

                $solicitud->cantidad = $item['cantidad'];
                $solicitud->porcentaje = $item['porcentaje'];
                $solicitud->estado = 'EN PROCESO';
                $solicitud->save();

            }

            DB::commit();
            return response()->json(['estado' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'estado' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Detalle de OT
    public function ajaxDetalleOT(Request $request)
    {
        $solicitudes = Solicitud::with(['producto', 'usuarioCreador'])
            ->whereJsonContains('ordenes_trabajo', $request->ot_id)
            ->get();

        $data = $solicitudes->map(fn($s) => [
            'id' => $s->id,
            'producto' => $s->producto->nombre ?? '-',
            'cantidad' => $s->cantidad,
            'estado' => $s->estado,
            'usuario' => $s->usuarioCreador->name ?? '-',
        ]);

        return response()->json(['estado' => true, 'data' => ['solicitudes' => $data]]);
    }

    public function accionProducto(Request $request)
    {
        $s = Solicitud::find($request->solicitud_id);
        if (!$s) {
            return response()->json(['estado' => false, 'mensaje' => 'Solicitud no encontrada']);
        }

        $ingresosSeleccionados = $request->input('ingreso'); // array de ingreso_id
        $cantidadRestante = $s->cantidad;

        DB::beginTransaction();
        try {
            if ($request->accion === 'aprobar') {

                foreach ($ingresosSeleccionados as $ingresoId) {
                    if ($cantidadRestante <= 0)
                        break;

                    $ingresoMovimiento = Movimiento::find($ingresoId);
                    if (!$ingresoMovimiento)
                        continue;


                    $ultimaSalida = Movimiento::where('movimiento_id', $ingresoId)
                        ->where('salida', '>', 0)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    $stockDisponible = ($ingresoMovimiento->ingreso ?? 0) - ($ultimaSalida->salida ?? 0);
                    if ($stockDisponible <= 0)
                        continue;


                    $cantidadASalir = min($cantidadRestante, $stockDisponible);


                    DB::table('movimientos')->insert([
                        'producto_id' => $s->producto_id,
                        'salida' => $cantidadASalir,
                        'ingreso' => 0,
                        'codigo_compra' => $ingresoMovimiento->codigo_compra,
                        'fecha' => now(),
                        'descripcion' => 'Salida por aprobación de solicitud #' . $s->id,
                        'usuario_creador_id' => Auth::id(),
                        'movimiento_id' => $ingresoId,
                        'estado' => 'ACTIVO',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $cantidadRestante -= $cantidadASalir;
                }

                if ($cantidadRestante > 0) {
                    DB::rollBack();
                    return response()->json([
                        'estado' => false,
                        'mensaje' => 'No hay suficiente stock en los ingresos seleccionados'
                    ]);
                }

                $s->estado = 'APROBADO';

            } else {
                $s->estado = 'RECHAZADO';
            }

            $s->save();
            DB::commit();

            return response()->json([
                'estado' => true,
                'mensaje' => 'Solicitud actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'estado' => false,
                'mensaje' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    //-----------------
    // public function accionProducto(Request $request)
    // {
    //     $s = Solicitud::find($request->solicitud_id);
    //     if (!$s) {
    //         return response()->json(['estado' => false, 'mensaje' => 'Solicitud no encontrada']);
    //     }

    //     $ingresoId = $request->input('ingreso');

    //     DB::beginTransaction();
    //     try {

    //         if ($request->accion === 'aprobar') {

    //             // 1️⃣ Obtener ingreso seleccionado
    //             $ingresoMovimiento = DB::table('movimientos')
    //                 ->where('id', $ingresoId)
    //                 ->first();

    //             if (!$ingresoMovimiento) {
    //                 return response()->json([
    //                     'estado' => false,
    //                     'mensaje' => 'Ingreso no válido'
    //                 ]);
    //             }

    //             // 2️⃣ Última salida de ese ingreso
    //             $ultimaSalida = DB::table('movimientos')
    //                 ->where('movimiento_id', $ingresoMovimiento->id)
    //                 ->where('salida', '>', 0)
    //                 ->orderBy('created_at', 'desc')
    //                 ->first();

    //             // 3️⃣ Stock disponible
    //             $stockDisponible =
    //                 ($ingresoMovimiento->ingreso ?? 0)
    //                 - ($ultimaSalida->salida ?? 0);

    //             if ($stockDisponible < $s->cantidad) {
    //                 return response()->json([
    //                     'estado' => false,
    //                     'mensaje' => 'No hay suficiente stock'
    //                 ]);
    //             }

    //             // 4️⃣ Insertar salida
    //             DB::table('movimientos')->insert([
    //                 'producto_id' => $s->producto_id,
    //                 'salida' => $s->cantidad,
    //                 'ingreso' => 0,
    //                 'codigo_compra' => $ingresoMovimiento->codigo_compra,
    //                 'fecha' => now(),
    //                 'descripcion' => 'Salida por aprobación de solicitud #' . $s->id,
    //                 'usuario_creador_id' => Auth::id(),
    //                 'movimiento_id' => $ingresoMovimiento->id,
    //                 'estado' => 'ACTIVO',
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);

    //             $s->estado = 'APROBADO';

    //         } else {
    //             $s->estado = 'RECHAZADO';
    //         }

    //         $s->save();
    //         DB::commit();

    //         return response()->json([
    //             'estado' => true,
    //             'mensaje' => 'Solicitud actualizada correctamente'
    //         ]);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'estado' => false,
    //             'mensaje' => 'Error: ' . $e->getMessage()
    //         ]);
    //     }
    // }

    //----------------------------------------------


    // public function otsPorFactura($factura_id)
    // {
    //     try {
    //         $factura = Factura::with('ordenTrabajos')->findOrFail($factura_id);

    //         $ots = $factura->ordenTrabajos->map(function ($ot) {
    //             return [
    //                 'ids' => $ot->id, // si son múltiples, podrías usar ->pluck('id')->toArray()
    //                 'nro_ot' => $ot->numero_ot,
    //                 'peso_total' => $ot->peso_total ?? 0
    //             ];
    //         });

    //         return response()->json($ots);
    //     } catch (\Exception $e) {
    //         \Log::error("Error al obtener OTs por factura: " . $e->getMessage());
    //         return response()->json(['error' => 'No se pudieron cargar las OTs'], 500);
    //     }
    // }

    public function otsPorFactura($factura_id)
    {
        try {
            $ots = Order_Trabajo::where('factura_id', $factura_id)
                ->where('estado', 'RECEPCIONADO')
                ->where('tipo', 'ORDEN_TRABAJO')
                ->get();

            if ($ots->isEmpty()) {
                return response()->json([]);
            }

            $agrupadas = $ots->groupBy('nro_ot')->map(function ($grupo, $nro_ot) {
                $ids = $grupo->pluck('id')->toArray();
                $peso_total = $grupo->sum('peso'); // columna real
                return [
                    'nro_ot' => $nro_ot,
                    'ids' => $ids,
                    'peso_total' => $peso_total,
                ];
            })->values();

            return response()->json($agrupadas);

        } catch (\Exception $e) {
            \Log::error("Error al obtener OTs por factura: " . $e->getMessage());
            return response()->json(['error' => 'No se pudieron cargar las OTs'], 500);
        }
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




    // Códigos de compra según producto
    public function codigosCompra()
    {
        $codigos = Movimiento::whereNotNull('codigo_compra')
            ->select('codigo_compra', DB::raw('SUM(ingreso) - SUM(salida) as stock'))
            ->groupBy('codigo_compra')
            ->having('stock', '>', 0)
            ->get();

        return response()->json($codigos);
    }



    // Obtener productos con stock según el código de compra
    public function productosConStock(Request $request)
    {
        $codigo = $request->codigo_compra;
        if (!$codigo)
            return response()->json([]);

        $productos = Movimiento::where('codigo_compra', $codigo)
            ->select('producto_id', DB::raw('SUM(ingreso) - SUM(salida) as stock'))
            ->groupBy('producto_id')
            ->having('stock', '>', 0)
            ->get();

        $productos = $productos->map(function ($p) {
            $nombre = DB::table('productos')->where('id', $p->producto_id)->value('nombre');
            return [
                'producto_id' => $p->producto_id,
                'nombre' => $nombre,
                'stock' => $p->stock
            ];
        });

        return response()->json($productos);
    }

    // public function verDetalleSolicitud(Request $request)
    // {

    //     if ($request->ajax()) {

    //         // dd($request->all());

    //         $factura_id = $request->input('factura');

    //         $facturasSolicitadas = Solicitud::join('order_trabajos as ot', function ($join) {
    //             $join->whereRaw('JSON_CONTAINS(solicitudes.ordenes_trabajo, CAST(ot.id AS JSON))');
    //         })
    //             ->select('solicitudes.*')
    //             ->where('ot.factura_id', $factura_id)
    //             ->groupBy('solicitudes.id')
    //             ->get();

    //         // dd($facturasSolicitadas);

    //         $data = $facturasSolicitadas->map(function ($s) {

    //             // SACAMOS PARA EL NUMERO DE OT
    //             $primerOtId = $s->ordenes_trabajo[0] ?? null;
    //             $nro_ot = null;
    //             if ($primerOtId) {
    //                 $ot = Order_trabajo::find($primerOtId);
    //                 $nro_ot = $ot->nro_ot ?? null;
    //             }

    //             // SACAMOS EL STOCK E INGRESOS DE CADA PRODCUTO SOLICITADO
    //             $queryIngreso = Movimiento::where('ingreso', '>', 0)
    //                 ->where('salida', 0)
    //                 ->whereNotNull('codigo_compra')
    //                 ->whereNotNull('precio')
    //                 ->where('producto_id', $s->producto_id);
    //             // ->get();

    //             $ingresos = $queryIngreso->get();

    //             $stock = [];

    //             foreach ($ingresos as $key => $ingreso) {
    //                 $querySalida = Movimiento::where('salida', '>', 0)
    //                     ->where('ingreso', 0)
    //                     ->whereNull('codigo_compra')
    //                     ->whereNull('precio')
    //                     ->where('producto_id', $s->producto_id)
    //                     ->where('movimiento_id', $ingreso->id)
    //                     ->sum('salida');

    //                 $stockProducto = $ingreso->ingreso - $querySalida;

    //                 if ($stockProducto > 0) {
    //                     $stock[] = [
    //                         'ID' => $ingreso->id,
    //                         'CODIGO_COMPRA' => $ingreso->codigo_compra,
    //                         'STOCK' => $ingreso->ingreso - $querySalida,
    //                     ];
    //                 }
    //             }

    //             return [
    //                 'id' => $s->id,
    //                 'producto' => $s->producto->nombre ?? '-',
    //                 'cantidad' => $s->cantidad,
    //                 'estado' => $s->estado,
    //                 'usuario' => $s->usuarioCreador->name ?? '-',
    //                 'nro_ot' => $nro_ot,
    //                 'stock' => $stock
    //             ];
    //         });

    //         $valores = [
    //             'solicitudes' => $data
    //         ];

    //         $data = Respuesta::success($valores, "Datos obtenidos correctamente");


    //     } else {
    //         $data = Respuesta::error(null, "Error al obtener los datos");
    //     }
    //     return $data;

    // }


    public function verDetalleSolicitud(Request $request)
    {
        if (!$request->ajax()) {
            return Respuesta::error(null, "Error al obtener los datos");
        }

        $factura_id = $request->input('factura');


        $solicitudes = Solicitud::with(['producto', 'usuarioCreador'])
            ->whereNull('deleted_at')
            ->get();

        $facturasSolicitadas = collect();

        foreach ($solicitudes as $s) {

            if (!is_array($s->ordenes_trabajo))
                continue;
            $pertenceFactura = false;
            foreach ($s->ordenes_trabajo as $item) {
                if (isset($item['factura_id']) && $item['factura_id'] == $factura_id) {
                    $pertenceFactura = true;
                    break;
                }
            }
            if (!$pertenceFactura)
                continue;


            $primerOtId = $s->ordenes_trabajo[0]['ots'][0] ?? null;
            $nro_ot = null;
            if ($primerOtId) {
                $ot = Order_trabajo::find($primerOtId);
                $nro_ot = $ot->nro_ot ?? null;
            }

            $movimientos = Movimiento::where('producto_id', $s->producto_id)
                ->whereNull('deleted_at')
                ->orderBy('created_at', 'asc')
                ->get();

            $codigos = $movimientos
                ->pluck('codigo_compra')
                ->filter()
                ->unique();

            $stock = [];

            foreach ($codigos as $codigo) {


                $ultimoIngreso = $movimientos
                    ->where('codigo_compra', $codigo)
                    ->where('ingreso', '>', 0)
                    ->sortByDesc('created_at')
                    ->first();

                if (!$ultimoIngreso)
                    continue;


                $ultimaSalida = $movimientos
                    ->where('codigo_compra', $codigo)
                    ->where('salida', '>', 0)
                    ->sortByDesc('created_at')
                    ->first();

                $stockDisponible = ($ultimoIngreso->ingreso ?? 0) - ($ultimaSalida->salida ?? 0);

                if ($stockDisponible > 0) {
                    $stock[] = [
                        'INGRESO_ID' => $ultimoIngreso->id,
                        'CODIGO_COMPRA' => $codigo,
                        'STOCK' => $stockDisponible
                    ];
                }
            }

            $facturasSolicitadas->push([
                'id' => $s->id,
                'producto_id' => $s->producto_id,
                'producto' => $s->producto->nombre ?? '-',
                'cantidad' => $s->cantidad,
                'estado' => $s->estado,
                'usuario' => $s->usuarioCreador->name ?? '-',
                'nro_ot' => $nro_ot,
                'stock' => $stock,
            ]);
        }

        return Respuesta::success([
            'solicitudes' => $facturasSolicitadas
        ], "Datos obtenidos correctamente");
    }

    public function ajaxProductoSolicitud(Request $request){

        if($request->ajax()){

            $solicitudes = Solicitud::with(['producto'])
                                    ->select('producto_id')
                                    ->where('estado', 'APROBADO')
                                    ->groupBy('producto_id')
                                    ->get();

            // dd($solicitudes);

            $valores = [
                'solicitudes' => $solicitudes
            ];

            $data = Respuesta::success($valores, "Datos obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function buscarSolicitudesProducto(Request $request){

        if($request->ajax()){

            $producto_id = $request->input('productoId');

            $solicitudes = Solicitud::where('producto_id', $producto_id)
                                    ->where('estado', 'APROBADO')
                                    ->get();
            $fac = "";

            $solicitudArray = [];

            foreach ($solicitudes as $key => $solicitud) {
                $ordenesTrabajo = $solicitud->ordenes_trabajo;
                $fac = "";
                foreach ($ordenesTrabajo as $key => $ordenTrabajo) {
                    $fac = $fac." | Fac/Or-Re ".$ordenTrabajo['nro_factura']." : [";
                    $ots = $ordenTrabajo['ots'];
                    foreach ($ots as $key => $ot) {
                        $ordenTrabajoBuscado = Order_trabajo::find($ot);
                        $fac = $fac." OT:".$ordenTrabajoBuscado->nro_ot;
                        if((count($ots)-1) == $key)
                            $fac =$fac."]";
                        else
                            $fac =$fac." - ";
                    }
                }

                $solicitudArray[$solicitud->id] = $fac;
            }

            $valores = [
                'solicitudes' => $solicitudes,
                'fac' => $solicitudArray
            ];

            $data = Respuesta::success($valores, "Datos obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }




}

