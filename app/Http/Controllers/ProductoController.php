<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public function listado(){

        $proveedores = Proveedor::all();

        return view ('producto.listado')->with(compact('proveedores'));
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            $usuario = Auth::user();

            //SACAMOS EL LISTADO
            $productos = Producto::all();

            $valores = [
                'listado' => view('producto.ajaxListado')->with(compact('productos', 'usuario'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarProducto(Request $request){

        if($request->ajax()){

            // dd($request->all());

            //AL INICIO DECLARACION DE VARIABLES
            $producto_id     = $request->input('id');
            $proveedor_id    = $request->input('proveedor_id');
            $nombre          = $request->input('nombre');
            $tipo            = $request->input('tipo');
            $codigo          = $request->input('codigo');
            $minimo_stock    = $request->input('minimo_stock');
            // $precio_producto = $request->input('precio_producto');
            $usuario         = Auth::user();

            if($producto_id == '0'){
                //LA CREACION DE UN NUEVa producto
                $producto = new Producto();
                $producto->usuario_creador_id = $usuario->id;

            }else{
                //LA EDICION DE UN NUEVO producto
                $producto = Producto::find($producto_id);
                $producto->usuario_modificador_id = $usuario->id;
            }

            $producto->proveedor_id = $proveedor_id;
            $producto->nombre       = $nombre;
            $producto->tipo         = $tipo;
            $producto->codigo       = $codigo;
            // $producto->precio       = $precio_producto;
            $producto->minimo_stock = $minimo_stock;
            $producto->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }

    public function eliminarProducto(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $producto_id = $request->input('producto');
            $usuario = Auth::user();

            //BUSCAMOS AL producto
            $producto = Producto::find($producto_id);
            $producto->usuario_eliminador_id = $usuario->id;
            $producto->save();

            //AHORA ELIMINAMOS
            Producto::destroy($producto_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{

            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }

    public function verHistorialIngresos(Request $request){

        if($request->ajax()){

            $producto_id = $request->input('producto');
            $sucursal_id = $request->input('sucursal');

            $movimientos = Movimiento::where('sucursal_id', $sucursal_id)
                                    ->where('producto_id', $producto_id)
                                    ->where('ingreso', '>',0)
                                    ->orderBy('id', 'DESC')
                                    ->get();

            $valores = [
                'listado' => view('producto.verHistorialIngresos')->with(compact('movimientos'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
