<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Utils\Respuesta;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function listado (){
        return view ('user.listado');
    }

    public function ajaxListado (Request $request){
        if($request->ajax()){
            //sacamos el listado
            $usuarios = User::all();
            $valores=[
                'listado' => view('user.ajaxListado')->with(compact('usuarios'))->render()
            ];
            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");

        }
        return $data;
    }
        
}
