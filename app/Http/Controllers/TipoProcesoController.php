<?php

namespace App\Http\Controllers;

use App\Models\Tipo_proceso;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoProcesoController extends Controller
{
    public function listado(){
        return view ('tipo_proceso.listado');
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $tipo_procesos = Tipo_proceso::all();

            $valores = [
                'listado' => view('tipo_proceso.ajaxListado')->with(compact('tipo_procesos'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarTipoProceso(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $tipo_proceso_id = $request->input('id');
            $nombre = $request->input('nombre');
            $usuario = Auth::user();
            
            if($tipo_proceso_id == '0'){
                //LA CREACION DE UN NUEVO TIPO PROCESO
                $tipo_proceso = new Tipo_proceso();
                $tipo_proceso->usuario_creador_id = $usuario->id;

            }else{
                //LA EDICION DE UN NUEVO TIPO PROCESO
                $tipo_proceso = Tipo_proceso::find($tipo_proceso_id);
                $tipo_proceso->usuario_modificador_id = $usuario->id;
            }

            $tipo_proceso->nombre = $nombre;
            $tipo_proceso->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
        
    }

    public function eliminarTipoProceso(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $tipo_proceso_id = $request->input('tipo_proceso');
            $usuario = Auth::user();

            //BUSCAMOS el TIPO PROCESO
            $tipo_proceso = Tipo_proceso::find($tipo_proceso_id);
            $tipo_proceso->usuario_eliminador_id = $usuario->id;
            $tipo_proceso->save();

            //AHORA ELIMINAMOS
            Tipo_proceso::destroy($tipo_proceso_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{
        
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
