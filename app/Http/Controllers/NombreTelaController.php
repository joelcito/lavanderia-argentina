<?php

namespace App\Http\Controllers;

use App\Models\Nombre_tela;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NombreTelaController extends Controller
{
    public function listado(){
        return view ('nombre_tela.listado');
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $nombre_telas = Nombre_tela::all();

            $valores = [
                'listado' => view('nombre_tela.ajaxListado')->with(compact('nombre_telas'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarNombreTela(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $nombre_tela_id = $request->input('id');
            $nombre = $request->input('nombre');
            $usuario = Auth::user();
            
            if($nombre_tela_id == '0'){
                //LA CREACION DE UN NUEVO nombre TELA
                $nombre_tela = new Nombre_tela();
                $nombre_tela->usuario_creador_id = $usuario->id;

            }else{
                //LA EDICION DE UN NUEVO nombre TELA
                $nombre_tela = Nombre_tela::find($nombre_tela_id);
                $nombre_tela->usuario_modificador_id = $usuario->id;
            }

            $nombre_tela->nombre = $nombre;
            $nombre_tela->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
        
    }

    public function eliminarNombreTela(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $nombre_tela_id = $request->input('nombre_tela');
            $usuario = Auth::user();

            //BUSCAMOS el TIPO TELA
            $nombre_tela = Nombre_tela::find($nombre_tela_id);
            $nombre_tela->usuario_eliminador_id = $usuario->id;
            $nombre_tela->save();

            //AHORA ELIMINAMOS
            Nombre_tela::destroy($nombre_tela_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{
        
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
