<?php

namespace App\Http\Controllers;

use App\Models\Color_tela;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColorTelaController extends Controller
{
    public function listado(){
        return view ('color_tela.listado');
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $color_telas = Color_tela::all();

            $valores = [
                'listado' => view('color_tela.ajaxListado')->with(compact('color_telas'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarColorTela(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $color_tela_id = $request->input('id');
            $nombre = $request->input('nombre');
            $usuario = Auth::user();
            
            if($color_tela_id == '0'){
                //LA CREACION DE UN NUEVO COLOR TELA
                $color_tela = new Color_tela();
                $color_tela->usuario_creador_id = $usuario->id;

            }else{
                //LA EDICION DE UN NUEVO COLOr TELA
                $color_tela = Color_tela::find($color_tela_id);
                $color_tela->usuario_modificador_id = $usuario->id;
            }

            $color_tela->nombre = $nombre;
            $color_tela->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
        
    }

    public function eliminarColorTela(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $color_tela_id = $request->input('color_tela');
            $usuario = Auth::user();

            //BUSCAMOS el TIPO TELA
            $color_tela = Color_tela::find($color_tela_id);
            $color_tela->usuario_eliminador_id = $usuario->id;
            $color_tela->save();

            //AHORA ELIMINAMOS
            Color_tela::destroy($color_tela_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{
        
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
