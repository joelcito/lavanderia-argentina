<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Models\Color_tela;
use App\Models\Focalizado;
use App\Models\Nevado;
use App\Models\Nombre_tela;
use App\Models\Prelavado;
use App\Models\Producto;
use App\Models\Receta;
use App\Models\Tipo_proceso;
use App\Models\Tipo_tela;
use App\Utils\Respuesta;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    public function listado()
    {
        $tipoTelas = Tipo_tela::all();
        $colorTelas = Color_tela::all();
        $nombreTelas = Nombre_tela::all();
        $prelavados = Prelavado::all();
        $focalizados = Focalizado::all();
        $nevados = Nevado::all();
        $caracteristicas = Caracteristica::all();
        $tipoProcesos = Tipo_proceso::all();
        $productos = Producto::all();

        return view('receta.listado')->with(compact('tipoTelas', 'colorTelas', 'nombreTelas', 'prelavados', 'focalizados', 'nevados', 'caracteristicas', 'tipoProcesos', 'productos'));
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $recetas = Receta::all();

            $valores = [
                'listado' => view('receta.ajaxListado')->with(compact('recetas'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }
}
