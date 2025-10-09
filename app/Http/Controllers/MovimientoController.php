<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Sucursal;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MovimientoController extends Controller
{
    public function listado(){
        $sucursales = Sucursal::all();
        return view ('movimiento.listado')->with(compact('sucursales'));
    }

    public function ajaxListado(Request $request){

        $productoId = $request->input('productoId');

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $stocks = Movimiento::where('producto_id', $productoId)
                ->select('sucursal_id')
                ->selectRaw('SUM(ingreso) - SUM(salida) as stock_sucursal')
                ->groupBy('sucursal_id')
                ->get();

            $valores = [
                'stock' => view('movimiento.ajaxListado')->with(compact('stocks', 'productoId'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    /**
     * Añadir ingreso de producto a una sucursal
     */
    public function guardarIngreso(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $movimiento_id = $request->input('id');
            $producto_id = $request->input('idProd');
            $sucursal_id = $request->input('idSuc');
            $ingreso = $request->input('cantidad_ingreso');
            $fecha = $request->input('fecha_ingreso');
            $descripcion = $request->input('descripcion');
            
            $usuario = Auth::user();
            
            //LA CREACION DE UN NUEVa movimiento
            $movimiento = new Movimiento();
            $movimiento->usuario_creador_id = $usuario->id;

            $movimiento->producto_id = $producto_id;
            $movimiento->sucursal_id = $sucursal_id;
            $movimiento->ingreso = $ingreso;
            $movimiento->fecha = $fecha;
            $movimiento->descripcion = $descripcion;
            
            $movimiento->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
        
    }

    /**
     * Añadir salida de producto a una sucursal
     */
    public function guardarSalida(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $movimiento_id = $request->input('id');
            $producto_id = $request->input('idProds');
            $sucursal_id = $request->input('idSucs');
            $salida = $request->input('cantidad_salida');
            $fecha = $request->input('fecha_salida');
            $descripcion = $request->input('descripcion');
            
            $usuario = Auth::user();
                       
            //LA CREACION DE UN NUEVa movimiento
            $movimiento = new Movimiento();
            $movimiento->usuario_creador_id = $usuario->id;


            $movimiento->producto_id = $producto_id;
            $movimiento->sucursal_id = $sucursal_id;
            $movimiento->salida = $salida;
            $movimiento->fecha = $fecha;
            $movimiento->descripcion = $descripcion;
            
            $movimiento->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
        
    }
}
