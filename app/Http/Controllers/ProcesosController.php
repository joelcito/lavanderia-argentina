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
        $request->validate([
            'maquinaria_id' => 'required|exists:maquinarias,id',
            'producto_id' => 'required|exists:productos,id',
            'tipo_proceso_id' => 'required|exists:tipo_procesos,id',
            'fecha_ingreso' => 'required|date',
            // otros campos según necesidad...
        ]);

        $proceso = Proceso::updateOrCreate(
            ['id' => $request->id],
            $request->only([
                'order_trabajo_id',
                'producto_id',
                'maquinaria_id',
                'tipo_proceso_id',
                'fecha_ingreso',
                'fecha_salida',
                'cantida',
                'porcentaje',
                'gr_litro',
                'tiempo',
                'temperatura',
                'ph',
                'rb',
                'descripcion',
                'estado'
            ])
        );

        return response()->json([
            'estado' => true,
            'mensaje' => 'Proceso guardado correctamente',
            'data' => $proceso
        ]);
    }


}
