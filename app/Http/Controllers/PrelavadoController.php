<?php

namespace App\Http\Controllers;

use App\Models\Prelavado;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrelavadoController extends Controller
{
    public function listado(){
        return view ('prelavado.listado');
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $prelavados = Prelavado::all();

            $valores = [
                'listado' => view('prelavado.ajaxListado')->with(compact('prelavados'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarPrelavado(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $prelavado_id = $request->input('id');
            $nombre = $request->input('nombre');
            $usuario = Auth::user();
            
            if($prelavado_id == '0'){
                //LA CREACION DE UN NUEVO PRELAVADO
                $prelavado = new Prelavado();
                $prelavado->usuario_creador_id = $usuario->id;

            }else{
                //LA EDICION DE UN NUEVO PRELAVADO
                $prelavado = Prelavado::find($prelavado_id);
                $prelavado->usuario_modificador_id = $usuario->id;
            }

            $prelavado->nombre = $nombre;
            $prelavado->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
        
    }

    public function eliminarPrelavado(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $prelavado_id = $request->input('prelavado');
            $usuario = Auth::user();

            //BUSCAMOS el PRELAVADO
            $prelavado = Prelavado::find($prelavado_id);
            $prelavado->usuario_eliminador_id = $usuario->id;
            $prelavado->save();

            //AHORA ELIMINAMOS
            Prelavado::destroy($prelavado_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{
        
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
