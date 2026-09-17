<?php

namespace App\Http\Controllers;

use App\Models\Nevado;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NevadoController extends Controller
{
    public function listado(){
        return view ('nevado.listado');
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $nevados = Nevado::all();

            $valores = [
                'listado' => view('nevado.ajaxListado')->with(compact('nevados'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarNevado(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $nombre_tela_id = $request->input('id');
            $nombre         = $request->input('nombre');
            $usuario        = Auth::user();

            // dd($request->all());

            if($nombre_tela_id == '0'){
                $nevado = new Nevado();
                $nevado->usuario_creador_id = $usuario->id;

            }else{
                $nevado = Nevado::find($nombre_tela_id);
                $nevado->usuario_modificador_id = $usuario->id;
            }

            $nevado->nombre = $nombre;
            $nevado->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }

    public function eliminarNevado(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $nombre_tela_id = $request->input('nombre_tela');
            $usuario = Auth::user();

            //BUSCAMOS el TIPO TELA
            $nombre_tela = Nevado::find($nombre_tela_id);
            $nombre_tela->usuario_eliminador_id = $usuario->id;
            $nombre_tela->save();

            //AHORA ELIMINAMOS
            Nevado::destroy($nombre_tela_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{

            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
