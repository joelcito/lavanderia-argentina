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


    // public function ajaxListado(Request $request)
    // {
    //     if (!$request->ajax()) {
    //         return Respuesta::error(null, "Petición no válida");
    //     }

    //     $solicitudes = Solicitud::with('usuarioCreador')
    //         ->whereNull('deleted_at')
    //         ->get();

    //     $facturasSolicitadas = collect();

    //     foreach ($solicitudes as $solicitud) {

    //         if (!is_array($solicitud->ordenes_trabajo)) {
    //             continue;
    //         }

    //         foreach ($solicitud->ordenes_trabajo as $item) {

    //             // 🔒 PROTECCIÓN CONTRA DATOS VIEJOS
    //             if (!is_array($item) || !isset($item['factura_id'])) {
    //                 continue;
    //             }

    //             $facturasSolicitadas->push((object) [
    //                 'factura_id' => $item['factura_id'],
    //                 'numero_factura' => $item['nro_factura'] ?? '',
    //                 'usuarioCreador' => $solicitud->usuarioCreador,
    //                 'ots' => $item['ots'] ?? [],
    //             ]);
    //         }
    //     }

    //     return Respuesta::success([
    //         'listado' => view(
    //             'solicitudes.ajaxListado',
    //             compact('facturasSolicitadas')
    //         )->render()
    //     ], "Datos obtenidos correctamente");
    // }


    public function ajaxListado(Request $request)
    {
        // if (!$request->ajax()) {
        //     return Respuesta::error(null, "Petición no válida");
        // }

        // $solicitudes = Solicitud::with(['producto', 'usuarioCreador'])
        //     ->whereNull('deleted_at')
        //     ->orderBy('created_at', 'desc')
        //     ->get();

        // return Respuesta::success([
        //     'listado' => view(
        //         'solicitudes.ajaxListado',
        //         compact('solicitudes')
        //     )->render()
        // ], "Datos obtenidos correctamente");
        if (!$request->ajax()) {
            return Respuesta::error(null, "Petición no válida");
        }

        $solicitudes = Solicitud::with(['producto', 'usuarioCreador'])
            ->whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();

        // 🔹 AQUÍ ES DONDE VA TODO
        foreach ($solicitudes as $solicitud) {

            $otsIds = [];

            if (is_array($solicitud->ordenes_trabajo)) {
                foreach ($solicitud->ordenes_trabajo as $item) {
                    if (!empty($item['ots'])) {
                        $otsIds = array_merge($otsIds, $item['ots']);
                    }
                }
            }

            // Buscar nro_ot según IDs
            $solicitud->nros_ot = Order_trabajo::whereIn('id', $otsIds)
                ->pluck('nro_ot'); // colección de nro_ot
        }

        return Respuesta::success([
            'listado' => view(
                'solicitudes.ajaxListado',
                compact('solicitudes')
            )->render()
        ], "Datos obtenidos correctamente");
    }

    public function store(Request $request)
    {

        // dd($request->all());

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
                        'producto_id'        => $s->producto_id,
                        'salida'             => $cantidadASalir,
                        'sucursal_id'        => $ingresoMovimiento->sucursal_id,
                        'solicitud_id'       => $s->id,
                        'ingreso'            => 0,
                        'codigo_compra'      => $ingresoMovimiento->codigo_compra,
                        'fecha'              => now(),
                        'descripcion'        => 'Salida por aprobación de solicitud #' . $s->id,
                        'usuario_creador_id' => Auth::id(),
                        'movimiento_id'      => $ingresoId,
                        'estado'             => 'ACTIVO',
                        'created_at'         => now(),
                        'updated_at'         => now(),
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

    public function otsPorFactura($factura_id)
    {
        try {

            // dd($factura_id);

            // $ots = Order_Trabajo::where('factura_id', $factura_id)
            //                     ->where('estado', 'RECEPCIONADO')
            //                     ->where('tipo', 'ORDEN_TRABAJO')
            //                     ->get();

            $ots = Order_Trabajo::where('factura_id', $factura_id)
                                ->where('tipo', 'ORDEN_TRABAJO')
                                ->where(function ($query) {
                                    $query->where('estado', 'RECEPCIONADO')
                                        ->orWhere('estado', 'TRABAJANDO');
                                })
                                ->get();

            // SACAMOS LAS SOLICITUDES CON ESE IF_FACTURA
            // $solicitudes = DB::table('solicitudes as s')
            //                 ->whereRaw("
            //                         EXISTS (
            //                             SELECT 1
            //                             FROM JSON_TABLE(
            //                                 s.ordenes_trabajo,
            //                                 '$[*]' COLUMNS (
            //                                     factura_id INT PATH '$.factura_id'
            //                                 )
            //                             ) jt
            //                             WHERE jt.factura_id = ?
            //                         )
            //                     ", [$factura_id])
            //                 ->get();

            $solicitudes = DB::table('solicitudes')
                                ->whereRaw("
                                    JSON_CONTAINS(
                                        JSON_EXTRACT(ordenes_trabajo, '$[*].factura_id'),
                                        ?
                                    )
                                ", [$factura_id])
                                ->get();

            $otsUsadas = collect();
            foreach ($solicitudes as $solicitud) {
                $items = json_decode($solicitud->ordenes_trabajo, true);
                foreach ($items as $item) {
                    if ($item['factura_id'] == $factura_id) {
                        foreach ($item['ots'] as $ot) {
                            $otsUsadas->push($ot);
                        }
                    }
                }
            }

            $otsUsadas = $otsUsadas->unique()->values();

            if ($ots->isEmpty()) {
                return response()->json([]);
            }

            $agrupadas = $ots->groupBy('nro_ot')->map(function ($grupo, $nro_ot) use ($otsUsadas) {
                $ids = $grupo->pluck('id')->toArray();
                $peso_total = $grupo->sum('peso');

                $coincide = collect($ids)->intersect($otsUsadas)->isNotEmpty();

                return [
                    'nro_ot'     => $nro_ot,
                    'ids'        => $ids,
                    'peso_total' => $peso_total,
                    'disabled'   => $coincide
                ];
            })->values();

            return response()->json($agrupadas);

        } catch (\Exception $e) {
            \Log::error("Error al obtener OTs por factura: " . $e->getMessage());
            return response()->json(['error' => 'No se pudieron cargar las OTs', 'message' => $e->getMessage()], 500);
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

    public function codigosCompra()
    {
        $codigos = Movimiento::whereNotNull('codigo_compra')
            ->select('codigo_compra', DB::raw('SUM(ingreso) - SUM(salida) as stock'))
            ->groupBy('codigo_compra')
            ->having('stock', '>', 0)
            ->get();

        return response()->json($codigos);
    }

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
    //     if (!$request->ajax()) {
    //         return Respuesta::error(null, "Error al obtener los datos");
    //     }

    //     $factura_id = $request->input('factura');


    //     $solicitudes = Solicitud::with(['producto', 'usuarioCreador'])
    //         ->whereNull('deleted_at')
    //         ->get();

    //     $facturasSolicitadas = collect();

    //     foreach ($solicitudes as $s) {

    //         if (!is_array($s->ordenes_trabajo))
    //             continue;
    //         $pertenceFactura = false;
    //         foreach ($s->ordenes_trabajo as $item) {
    //             if (isset($item['factura_id']) && $item['factura_id'] == $factura_id) {
    //                 $pertenceFactura = true;
    //                 break;
    //             }
    //         }
    //         if (!$pertenceFactura)
    //             continue;


    //         $primerOtId = $s->ordenes_trabajo[0]['ots'][0] ?? null;
    //         $nro_ot = null;
    //         if ($primerOtId) {
    //             $ot = Order_trabajo::find($primerOtId);
    //             $nro_ot = $ot->nro_ot ?? null;
    //         }

    //         $movimientos = Movimiento::where('producto_id', $s->producto_id)
    //             ->whereNull('deleted_at')
    //             ->orderBy('created_at', 'asc')
    //             ->get();

    //         $codigos = $movimientos
    //             ->pluck('codigo_compra')
    //             ->filter()
    //             ->unique();

    //         $stock = [];

    //         foreach ($codigos as $codigo) {


    //             $ultimoIngreso = $movimientos
    //                 ->where('codigo_compra', $codigo)
    //                 ->where('ingreso', '>', 0)
    //                 ->sortByDesc('created_at')
    //                 ->first();

    //             if (!$ultimoIngreso)
    //                 continue;


    //             $ultimaSalida = $movimientos
    //                 ->where('codigo_compra', $codigo)
    //                 ->where('salida', '>', 0)
    //                 ->sortByDesc('created_at')
    //                 ->first();

    //             $stockDisponible = ($ultimoIngreso->ingreso ?? 0) - ($ultimaSalida->salida ?? 0);

    //             if ($stockDisponible > 0) {
    //                 $stock[] = [
    //                     'INGRESO_ID' => $ultimoIngreso->id,
    //                     'CODIGO_COMPRA' => $codigo,
    //                     'STOCK' => $stockDisponible
    //                 ];
    //             }
    //         }

    //         $facturasSolicitadas->push([
    //             'id' => $s->id,
    //             'producto_id' => $s->producto_id,
    //             'producto' => $s->producto->nombre ?? '-',
    //             'cantidad' => $s->cantidad,
    //             'estado' => $s->estado,
    //             'usuario' => $s->usuarioCreador->name ?? '-',
    //             'nro_ot' => $nro_ot,
    //             'stock' => $stock,
    //         ]);
    //     }

    //     return Respuesta::success([
    //         'solicitudes' => $facturasSolicitadas
    //     ], "Datos obtenidos correctamente");
    // }


    public function verDetalleSolicitud(Request $request)
    {
        if (!$request->ajax()) {
            return Respuesta::error(null, "Error al obtener los datos");
        }

        $solicitud = Solicitud::with(['producto', 'usuarioCreador'])
            ->find($request->solicitud_id);

        if (!$solicitud) {
            return Respuesta::error(null, "Solicitud no encontrada");
        }

        //🔹 Obtener nro_ot (primer OT referencial)
        // $nro_ot = null;
        // if (is_array($solicitud->ordenes_trabajo)) {
        //     $primerOtId = $solicitud->ordenes_trabajo[0]['ots'][0] ?? null;
        //     if ($primerOtId) {
        //         $ot = Order_trabajo::find($primerOtId);
        //         $nro_ot = $ot->nro_ot ?? null;
        //     }
        // }
        $nros_ot = [];

        if (is_array($solicitud->ordenes_trabajo)) {

            $otsIds = [];

            foreach ($solicitud->ordenes_trabajo as $item) {
                if (!empty($item['ots'])) {
                    $otsIds = array_merge($otsIds, $item['ots']);
                }
            }

            if (!empty($otsIds)) {
                $nros_ot = Order_trabajo::whereIn('id', $otsIds)
                    ->pluck('nro_ot')
                    ->toArray();
            }
        }



        // 🔹 Stock disponible
        $movimientos = Movimiento::where('producto_id', $solicitud->producto_id)
            ->orderBy('created_at', 'asc')
            ->get();

        $stock = [];

        foreach ($movimientos->groupBy('codigo_compra') as $codigo => $movs) {

            $ingreso = $movs->where('ingreso', '>', 0)->last();
            $salida = $movs->where('salida', '>', 0)->last();

            $disponible = ($ingreso->ingreso ?? 0) - ($salida->salida ?? 0);

            if ($disponible > 0) {
                $stock[] = [
                    'INGRESO_ID' => $ingreso->id,
                    'CODIGO_COMPRA' => $codigo,
                    'STOCK' => $disponible
                ];
            }
        }

        return Respuesta::success([
            'solicitudes' => [
                [
                    'id' => $solicitud->id,
                    'producto' => $solicitud->producto->nombre ?? '-',
                    'cantidad' => $solicitud->cantidad,
                    'estado' => $solicitud->estado,
                    'usuario' => $solicitud->usuarioCreador->name ?? '-',
                    'nro_ot' => $nros_ot,
                    'stock' => $stock
                ]
            ]
        ]);
    }

    public function ajaxProductoSolicitud(Request $request)
    {

        if ($request->ajax()) {

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

    public function buscarSolicitudesProducto(Request $request)
    {

        if ($request->ajax()) {

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
                    $fac = $fac . " | Fac/Or-Re " . $ordenTrabajo['nro_factura'] . " : [";
                    $ots = $ordenTrabajo['ots'];
                    $arrayOts = array();
                    foreach ($ots as $key => $ot) {
                        $ordenTrabajoBuscado = Order_trabajo::find($ot);
                        if(!in_array($ordenTrabajoBuscado->nro_ot, $arrayOts)){
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
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }




}

