<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Pago;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Producto;
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
        return view('procesos.listado', compact('maquinarias'));
    }



    public function ajaxListado(Request $request)
    {
        if ($request->ajax()) {

            // Obtener procesos con relaciones
            $procesos = Proceso::all();
            $valores = [
                'listado' => view('procesos.ajaxListado')
                    ->with(compact('procesos'))
                    ->render()
            ];


            $data = Respuesta::success($valores, "Datos obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
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
        // Validación
        $request->validate([
            'maquinaria_id' => 'required|exists:maquinarias,id',
            'producto_id' => 'required|exists:productos,id',
            'tipo_proceso_id' => 'required|exists:tipo_procesos,id',
            'fecha_ingreso' => 'required|date',
        ]);

        // Obtener maquinaria
        $maquinaria = Maquinaria::find($request->maquinaria_id);

        // Validar que esté disponible
        if ($maquinaria->estado_maquina !== 'DISPONIBLE') {
            return response()->json([
                'estado' => false,
                'mensaje' => 'La maquinaria seleccionada no está disponible para asignar un proceso.'
            ], 422);
        }

        // Crear / actualizar proceso
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
                'estado' => 'EN PROCESO'
            ]
        );

        // Cambiar estado de la maquinaria automáticamente
        $maquinaria->estado_maquina = 'EN PROCESO';
        $maquinaria->save();

        return response()->json([
            'estado' => true,
            'mensaje' => 'Proceso registrado correctamente.',
            'data' => $proceso
        ]);
    }

    public function infoMaquinaria(Request $request)
    {
        $maquinaria = Maquinaria::withCount([
            'procesos' => function ($q) {
                $q->where('estado', 'PENDIENTE')->orWhere('estado', 'EN_PROCESO');
            }
        ])->find($request->id);

        if (!$maquinaria) {
            return response()->json(['estado_maquina' => 'NO DISPONIBLE', 'procesos_activos' => 0]);
        }

        // Determinar estado de la máquina
        $estado = ($maquinaria->procesos_count >= 3) ? 'NO DISPONIBLE' : 'DISPONIBLE';

        return response()->json([
            'estado_maquina' => $estado,
            'procesos_activos' => $maquinaria->procesos_count
        ]);
    }

    public function actualizarEstados()
    {
        // Obtener todos los procesos activos que no estén finalizados
        $procesos = Proceso::whereIn('estado', ['PENDIENTE', 'EN_PROCESO'])->get();

        foreach ($procesos as $proceso) {
            $ahora = now();

            // Si la fecha de salida ya pasó, marcar como finalizado
            if ($ahora >= $proceso->fecha_salida) {
                $proceso->estado = 'FINALIZADO';
                $proceso->save();
            }
        }

        return response()->json(['estado' => true]);
    }

}
