<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\SubCategoria;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubCategoriaController extends Controller
{
    public function listado(){
        // $categorias = Categoria::all();
        $categorias = Categoria::where('estado', 'PAGO')->get();
        return view('subcategoria.listado')->with(compact('categorias'));
    }

    public function ajaxListado(Request $request){
        if($request->ajax()){
            // $categorias = SubCategoria::all();
            $categorias = SubCategoria::where('estado', 'PAGO')->get();
            $valores = [
                'listado' => view('subcategoria.ajaxListado')->with(compact('categorias'))->render()
            ];
            $data = Respuesta::success($valores, "Datos obtenidos correctamente");
        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardar(Request $request){
        if($request->ajax()){

            // dd($request->all());

            $request->validate([
                'nombre' => 'required',
                'categoria_id' => 'required',
            ]);

            $id = $request->input('id');

            $nombre       = $request->input('nombre');
            $categoria_id = $request->input('categoria_id');
            $descripcion  = $request->input('descripcion');
            $usuario      = Auth::user();

            if( $id == 0 ){
                $categoria                     = new SubCategoria();
                $categoria->usuario_creador_id = $usuario->id;
            }else{
                $categoria = SubCategoria::find($id);
                $categoria->usuario_modificador_id = $usuario->id;
            }

            $categoria->nombre       = $nombre;
            $categoria->descripcion  = $descripcion;
            $categoria->categoria_id = $categoria_id;
            $categoria->estado       = "PAGO";
            $categoria->save();

            $data = Respuesta::success(null, "Datos obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "No existe");
        }
        return $data;
    }

    public function eliminar(Request $request){
        if($request->ajax()){

            $id = $request->input('id');
            $usuario = Auth::user();

            $categoria = SubCategoria::find($id);
            $categoria->usuario_eliminador_id = $usuario->id;
            $categoria->save();

            SubCategoria::destroy($id);

            $data = Respuesta::success(null, "Datos obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "No existe");
        }
        return $data;
    }
}
