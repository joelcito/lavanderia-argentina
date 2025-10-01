<?php

namespace App\Http\Controllers;

use App\Models\Maquinaria;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaquinariaController extends Controller
{
    public function listado(){
        return view ('maquinaria.listado');
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $maquinarias = Maquinaria::all();

            $valores = [
                'listado' => view('maquinaria.ajaxListado')->with(compact('maquinarias'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarMaquinaria(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $maquinaria_id = $request->input('id');
            $tipo = $request->input('tipo');
            $numero = $request->input('numero');
            $descripcion = $request->input('descripcion');
            $usuario = Auth::user();
            
            if($maquinaria_id == '0'){
                //LA CREACION DE UN NUEVa maquinaria
                $maquinaria = new Maquinaria();
                $maquinaria->usuario_creador_id = $usuario->id;

            }else{
                //LA EDICION DE UN NUEVO maquinaria
                $maquinaria = Maquinaria::find($maquinaria_id);
                $maquinaria->usuario_modificador_id = $usuario->id;
            }

            $maquinaria->tipo = $tipo;
            $maquinaria->numero = $numero;
            $maquinaria->descripcion = $descripcion;
            $maquinaria->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
        
    }

    public function eliminarMaquinaria(Request $request){

        if($request->ajax()){

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

        }else{
        
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
