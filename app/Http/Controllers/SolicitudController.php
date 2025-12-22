<?php

namespace App\Http\Controllers;

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
        $solicitudes = Solicitud::with(['producto', 'ordenTrabajo', 'usuarioCreador'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('solicitudes.listado', compact('solicitudes'));
    }
    public function ajaxListado(Request $request)
    {
        try {
            $solicitudes = Solicitud::with(['producto', 'ordenTrabajo', 'usuarioCreador'])
                ->orderBy('created_at', 'desc')
                ->get();


            $html = view('solicitudes.ajaxListado', compact('solicitudes'))->render();
            return response()->json([
                'estado' => true,
                'data' => ['listado' => $html]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error listado solicitudes: ' . $e->getMessage());
            return response()->json([
                'estado' => false,
                'mensaje' => 'Error al cargar solicitudes'
            ]);
        }
    }


    public function store(Request $request)
    {
        $request->validate([
            'orden_trabajo_id' => 'required|exists:order_trabajos,id',
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|numeric|min:0.01',
        ]);

        $userId = auth()->id(); // usuario que está creando la solicitud

        foreach ($request->productos as $prod) {
            Solicitud::create([
                'usuario_creador_id' => $userId,
                'producto_id' => $prod['producto_id'],
                'orden_trabajo_id' => $request->orden_trabajo_id,
                'cantidad' => $prod['cantidad'],
                'estado' => 'EN ESPERA',
            ]);
        }

        return response()->json([
            'estado' => true,
            'mensaje' => 'Solicitud registrada correctamente'
        ]);
    }


    public function ajaxDetalleOT(Request $request)
    {
        $solicitudes = Solicitud::with(['producto', 'usuarioCreador'])
            ->where('orden_trabajo_id', $request->ot_id)
            ->get();

        $data = $solicitudes->map(function ($s) {
            return [
                'id' => $s->id,
                'producto' => $s->producto->nombre ?? '-',
                'cantidad' => $s->cantidad,
                'estado' => $s->estado,
                'usuario' => $s->usuarioCreador->name ?? '-',
            ];
        });

        return response()->json(['estado' => true, 'data' => ['solicitudes' => $data]]);
    }

    // Para aprobar/rechazar producto
    public function accionProducto(Request $request)
    {
        // Buscar la solicitud
        $s = Solicitud::find($request->solicitud_id);
        if (!$s) {
            return response()->json([
                'estado' => false,
                'mensaje' => 'Solicitud no encontrada'
            ]);
        }

        // Calcular stock disponible según movimientos
        $stock = \DB::table('movimientos')
            ->where('producto_id', $s->producto_id)
            ->sum('ingreso') - \DB::table('movimientos')
                ->where('producto_id', $s->producto_id)
                ->sum('salida');

        if ($request->accion == 'aprobar') {

            // Validar stock
            if ($stock < $s->cantidad) {
                return response()->json([
                    'estado' => false,
                    'mensaje' => 'No hay suficiente stock, rechaza la solicitud'
                ]);
            }

            // Registrar salida en movimientos
            \DB::table('movimientos')->insert([
                'producto_id' => $s->producto_id,
                'orden_trabajo_id' => $s->orden_trabajo_id,
                'salida' => $s->cantidad,
                'ingreso' => 0,
                'fecha' => now(),
                'descripcion' => 'Salida por aprobación de solicitud #' . $s->id,
                'estado' => 'ACTIVO',
                'usuario_creador_id' => auth()->id(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Cambiar estado de la solicitud
            $s->estado = 'APROBADO';
        } else {
            // Si la acción no es aprobar, se rechaza
            $s->estado = 'RECHAZADO';
        }

        $s->save();

        return response()->json([
            'estado' => true,
            'mensaje' => 'Solicitud actualizada',
            'ot_id' => $s->orden_trabajo_id
        ]);
    }

    public function productosSolicitudesAceptadas(Request $request)
    {
        $ot_id = $request->get('ot_id');

        if (!$ot_id) {
            return response()->json([]);
        }

        $productos = Producto::whereHas('solicitudes', function ($q) use ($ot_id) {
            $q->where('orden_trabajo_id', $ot_id)   // <- nombre correcto
                ->where('estado', 'APROBADO')
                ->whereNull('deleted_at');
        })->get();

        return response()->json($productos);
    }


}