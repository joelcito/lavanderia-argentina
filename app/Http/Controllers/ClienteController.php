<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Utils\Respuesta;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function listado (){
        return view ('cliente.listado');
    }

    public function ajaxListado (Request $request){
        if($request->ajax()){
            //sacamos el listado
            $usuarios = Cliente::all();
            $valores=[
                'listado' => view('cliente.ajaxListado')->with(compact('clientes'))->render()
            ];
            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");

        }
        return $data;
    }
}
