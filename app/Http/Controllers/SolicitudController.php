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

use Illuminate\Support\Facades\DB;

class SolicitudController extends Controller
{
    public function listado()
    {
        $facturas = Factura::with('ordenTrabajos')->get(); // ← aquí es clave
        $ordenes = Order_Trabajo::all();
        $solicitudes = Solicitud::with(['producto', 'ordenTrabajo', 'usuarioCreador'])
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
        $solicitudes = Solicitud::with(['producto', 'usuarioCreador'])->get();

        // Aplanamos todos los arrays de OT y agrupamos por OT individual
        $ots = $solicitudes->flatMap(fn($s) => collect($s->orden_trabajo_id))
            ->groupBy(fn($id) => $id);

        $html = view('solicitudes.ajaxListado', compact('ots'))->render();

        return response()->json([
            'estado' => true,
            'data' => ['listado' => $html]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->input('solicitudes');

        foreach ($data as $item) {
            $solicitud = new Solicitud();
            $solicitud->usuario_creador_id = auth()->id();
            $solicitud->producto_id = $item['producto_id'];
            $solicitud->orden_trabajo_id = json_encode($item['orden_trabajo_id']); // guardamos array como JSON
            $solicitud->cantidad = $item['cantidad']; // ya calculada según porcentaje
            $solicitud->porcentaje = $item['porcentaje']; // nuevo campo
            $solicitud->estado = 'EN PROCESO';
            $solicitud->save();
        }

        return response()->json(['estado' => true, 'mensaje' => 'Solicitudes guardadas correctamente']);
    }

    // Detalle de OT
    public function ajaxDetalleOT(Request $request)
    {
        $solicitudes = Solicitud::with(['producto', 'usuarioCreador'])
            ->whereJsonContains('orden_trabajo_id', $request->ot_id)
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

    // Aprobar o rechazar solicitud
    // public function accionProducto(Request $request)
    // {
    //     $s = Solicitud::find($request->solicitud_id);
    //     if (!$s) {
    //         return response()->json(['estado' => false, 'mensaje' => 'Solicitud no encontrada']);
    //     }

    //     $stock = DB::table('movimientos')
    //         ->where('producto_id', $s->producto_id)
    //         ->sum('ingreso') - DB::table('movimientos')->where('producto_id', $s->producto_id)->sum('salida');

    //     if ($request->accion == 'aprobar') {
    //         if ($stock < $s->cantidad) {
    //             return response()->json(['estado' => false, 'mensaje' => 'No hay suficiente stock']);
    //         }

    //         DB::table('movimientos')->insert([
    //             'producto_id' => $s->producto_id,
    //             'orden_trabajo_id' => json_encode($s->orden_trabajo_id), // guardamos array
    //             'salida' => $s->cantidad,
    //             'ingreso' => 0,
    //             'fecha' => now(),
    //             'descripcion' => 'Salida por aprobación de solicitud #' . $s->id,
    //             'estado' => 'ACTIVO',
    //             'usuario_creador_id' => Auth::id(),
    //             'created_at' => now(),
    //             'updated_at' => now()
    //         ]);

    //         $s->estado = 'APROBADO';
    //     } else {
    //         $s->estado = 'RECHAZADO';
    //     }

    //     $s->save();

    //     return response()->json(['estado' => true, 'mensaje' => 'Solicitud actualizada']);
    // }


    public function accionProducto(Request $request)
    {
        $s = Solicitud::find($request->solicitud_id);
        if (!$s) {
            return response()->json(['estado' => false, 'mensaje' => 'Solicitud no encontrada']);
        }

        // Calcular stock disponible
        $stock = DB::table('movimientos')
            ->where('producto_id', $s->producto_id)
            ->sum('ingreso') - DB::table('movimientos')->where('producto_id', $s->producto_id)->sum('salida');

        DB::beginTransaction();
        try {
            if ($request->accion == 'aprobar') {
                if ($stock < $s->cantidad) {
                    return response()->json(['estado' => false, 'mensaje' => 'No hay suficiente stock']);
                }

                // Insertar registro de salida en movimientos
                DB::table('movimientos')->insert([
                    'producto_id' => $s->producto_id,
                    'orden_trabajo_id' => json_encode($s->orden_trabajo_id), // Guardar array como JSON
                    'salida' => $s->cantidad,
                    'ingreso' => 0,
                    'fecha' => now(),
                    'descripcion' => 'Salida por aprobación de solicitud #' . $s->id,
                    'estado' => 'ACTIVO',
                    'usuario_creador_id' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Cambiar estado de la solicitud
                $s->estado = 'APROBADO';
            } else {
                // Rechazar solicitud
                $s->estado = 'RECHAZADO';
            }

            $s->save();
            DB::commit();

            return response()->json(['estado' => true, 'mensaje' => 'Solicitud actualizada']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'estado' => false,
                'mensaje' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ]);
        }
    }



    public function otsPorFactura($factura_id)
    {
        $ots = DB::table('order_trabajos')
            ->where('factura_id', $factura_id)
            ->where('tipo', 'ORDEN_TRABAJO')
            ->select(
                'nro_ot',
                DB::raw('GROUP_CONCAT(id) as ids'),
                DB::raw('SUM(peso) as peso_total')
            )
            ->groupBy('nro_ot')
            ->orderBy('nro_ot')
            ->get();

        return response()->json($ots);
    }



    // Códigos de compra según producto
    public function codigosCompra(Request $request)
    {
        $codigos = Movimiento::whereNotNull('codigo_compra')
            ->select(
                'codigo_compra',
                DB::raw('SUM(ingreso) - SUM(salida) as stock')
            )
            ->groupBy('codigo_compra')
            ->having('stock', '>', 0)
            ->get();

        return response()->json($codigos);
    }

    // Obtener productos con stock según el código de compra
    public function productosConStock(Request $request)
    {
        $codigo = $request->codigo_compra;

        if (!$codigo) {
            return response()->json([]);
        }

        $productos = Movimiento::where('codigo_compra', $codigo)
            ->select(
                'producto_id',
                DB::raw('SUM(ingreso) - SUM(salida) as stock')
            )
            ->groupBy('producto_id')
            ->having('stock', '>', 0)
            ->get();

        // Obtener nombres de los productos
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


}