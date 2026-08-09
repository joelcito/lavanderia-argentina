<?php

namespace App\Http\Controllers;

use App\Models\Maquinaria;
use App\Models\Proceso;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaquinariaController extends Controller
{
    public function listado()
    {
        return view('maquinaria.listado');
    }

    public function ajaxListado(Request $request)
    {

        if ($request->ajax()) {

            //SACAMOS EL LISTADO
            $maquinarias = Maquinaria::all();

            $valores = [
                'listado' => view('maquinaria.ajaxListado')->with(compact('maquinarias'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarMaquinaria(Request $request)
    {

        if ($request->ajax()) {

            //AL INICIO DECLARACION DE VARIABLES
            $maquinaria_id = $request->input('id');
            $tipo = $request->input('tipo');
            $numero = $request->input('numero');
            $descripcion = $request->input('descripcion');
            $estado_maquina = $request->input('estado_maquina');
            $usuario = Auth::user();

            if ($maquinaria_id == '0') {
                //LA CREACION DE UN NUEVa maquinaria
                $maquinaria = new Maquinaria();
                $maquinaria->usuario_creador_id = $usuario->id;

            } else {
                //LA EDICION DE UN NUEVO maquinaria
                $maquinaria = Maquinaria::find($maquinaria_id);
                $maquinaria->usuario_modificador_id = $usuario->id;
            }

            $maquinaria->tipo = $tipo;
            $maquinaria->numero = $numero;
            $maquinaria->descripcion = $descripcion;
            $maquinaria->estado_maquina = $estado_maquina;
            $maquinaria->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }

    public function eliminarMaquinaria(Request $request)
    {

        if ($request->ajax()) {

            //INICIALIZAMOS LAS VARIABLES
            $maquinaria_id = $request->input('maquinaria');
            $usuario = Auth::user();

            //BUSCAMOS AL maquinaria
            $maquinaria = Maquinaria::find($maquinaria_id);
            $maquinaria->usuario_eliminador_id = $usuario->id;
            $maquinaria->save();

            //AHORA ELIMINAMOS
            Maquinaria::destroy($maquinaria_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        } else {

            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }

    public function info(Request $request)
    {
        $maquina = Maquinaria::find($request->id);

        if (!$maquina) {
            return response()->json(['error' => 'Maquinaria no encontrada'], 404);
        }

        $procesosActivos = Proceso::where('maquinaria_id', $maquina->id)
            ->whereNull('fecha_salida')
            ->count();

        return response()->json([
            'id' => $maquina->id,
            'estado_maquina' => $maquina->estado_maquina,
            'procesos_activos' => $procesosActivos,
        ]);
    }

    public function index()
    {
        $maquinarias = Maquinaria::withCount([
            'procesos as procesos_activos' => function ($q) {
                $q->where('estado', 'ACTIVO'); // O el estado que uses
            }
        ])->get();

        foreach ($maquinarias as $m) {
            if ($m->procesos_activos >= 3) {
                $m->estado_maquina = "NO DISPONIBLE";
            } else {
                $m->estado_maquina = "DISPONIBLE";
            }
        }

        return view('maquinaria.index', compact('maquinarias'));
    }


}
