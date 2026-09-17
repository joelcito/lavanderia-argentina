<?php

namespace App\Http\Controllers;

use App\Models\Focalizado;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FocalizadoController extends Controller
{
    public function listado(){
        return view ('focalizado.listado');
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $focalizados = Focalizado::all();

            $valores = [
                'listado' => view('focalizado.ajaxListado')->with(compact('focalizados'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarFocalizado(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $focalizado_id = $request->input('id');
            $nombre = $request->input('nombre');
            $usuario = Auth::user();
            
            if($focalizado_id == '0'){
                //LA CREACION DE UN NUEVO FOCALIZADO
                $focalizado = new Focalizado();
                $focalizado->usuario_creador_id = $usuario->id;

            }else{
                //LA EDICION DE UN NUEVO FOCALIZADO
                $focalizado = Focalizado::find($focalizado_id);
                $focalizado->usuario_modificador_id = $usuario->id;
            }

            $focalizado->nombre = $nombre;
            $focalizado->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
        
    }

    public function eliminarFocalizado(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $focalizado_id = $request->input('focalizado');
            $usuario = Auth::user();

            //BUSCAMOS el FOCALIZADO
            $focalizado = Focalizado::find($focalizado_id);
            $focalizado->usuario_eliminador_id = $usuario->id;
            $focalizado->save();

            //AHORA ELIMINAMOS
            Focalizado::destroy($focalizado_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{
        
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
