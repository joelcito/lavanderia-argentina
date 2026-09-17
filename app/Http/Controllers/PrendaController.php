<?php

namespace App\Http\Controllers;

use App\Models\Prenda;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrendaController extends Controller
{
    public function listado(){
        return view ('prenda.listado');
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $prendas = Prenda::all();

            $valores = [
                'listado' => view('prenda.ajaxListado')->with(compact('prendas'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarPrenda(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $prenda_id        = $request->input('id');
            $nombre           = $request->input('nombre');
            $precio_planchado = $request->input('precio_planchado');
            $usuario          = Auth::user();

            if($prenda_id == '0'){
                //LA CREACION DE UN NUEVa PRENDA
                $prenda = new Prenda();
                $prenda->usuario_creador_id = $usuario->id;

            }else{
                //LA EDICION DE UN NUEVO ROL
                $prenda = Prenda::find($prenda_id);
                $prenda->usuario_modificador_id = $usuario->id;
            }

            $prenda->nombre           = $nombre;
            $prenda->precio_planchado = $precio_planchado;
            $prenda->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }

    public function eliminarPrenda(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $prenda_id = $request->input('prenda');
            $usuario = Auth::user();

            //BUSCAMOS AL PRENDA
            $prenda = Prenda::find($prenda_id);
            $prenda->usuario_eliminador_id = $usuario->id;
            $prenda->save();

            //AHORA ELIMINAMOS
            Prenda::destroy($prenda_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{

            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
