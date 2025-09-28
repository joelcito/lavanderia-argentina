<?php

namespace App\Http\Controllers;

use App\Models\Tipo_tela;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TipoTelaController extends Controller
{
    public function listado(){
        return view ('tipo_tela.listado');
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $tipo_telas = Tipo_tela::all();

            $valores = [
                'listado' => view('tipo_tela.ajaxListado')->with(compact('tipo_telas'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarTipoTela(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $tipo_tela_id = $request->input('id');
            $nombre = $request->input('nombre');
            $usuario = Auth::user();
            
            if($tipo_tela_id == '0'){
                //LA CREACION DE UN NUEVO TIPO TELA
                $tipo_tela = new Tipo_tela();
                $tipo_tela->usuario_creador_id = $usuario->id;

            }else{
                //LA EDICION DE UN NUEVO ROL
                $tipo_tela = Tipo_tela::find($tipo_tela_id);
                $tipo_tela->usuario_modificador_id = $usuario->id;
            }

            $tipo_tela->nombre = $nombre;
            $tipo_tela->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
        
    }

    public function eliminarTipoTela(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $tipo_tela_id = $request->input('tipo_tela');
            $usuario = Auth::user();

            //BUSCAMOS el TIPO TELA
            $tipo_tela = Tipo_tela::find($tipo_tela_id);
            $tipo_tela->usuario_eliminador_id = $usuario->id;
            $tipo_tela->save();

            //AHORA ELIMINAMOS
            Tipo_tela::destroy($tipo_tela_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{
        
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
