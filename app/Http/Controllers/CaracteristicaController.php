<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CaracteristicaController extends Controller
{
    public function listado(){
        return view ('caracteristica.listado');
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $caracteristicas = Caracteristica::all();

            $valores = [
                'listado' => view('caracteristica.ajaxListado')->with(compact('caracteristicas'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarCaracteristica(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $caracteristica_id = $request->input('id');
            $nombre = $request->input('nombre');
            $usuario = Auth::user();
            
            if($caracteristica_id == '0'){
                //LA CREACION DE UN NUEVa CARATERISTICA
                $caracteristica = new Caracteristica();
                $caracteristica->usuario_creador_id = $usuario->id;

            }else{
                //LA EDICION DE UN NUEVO CARACTERISTICA
                $caracteristica = Caracteristica::find($caracteristica_id);
                $caracteristica->usuario_modificador_id = $usuario->id;
            }

            $caracteristica->nombre = $nombre;
            $caracteristica->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
        
    }

    public function eliminarCaracteristica(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $caracteristica_id = $request->input('caracteristica');
            $usuario = Auth::user();

            //BUSCAMOS AL CARACTERISTICA
            $caracteristica = Caracteristica::find($caracteristica_id);
            $caracteristica->usuario_eliminador_id = $usuario->id;
            $caracteristica->save();

            //AHORA ELIMINAMOS
            Caracteristica::destroy($caracteristica_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{
        
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
