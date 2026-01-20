<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Sucursal;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            /*$stocks = Movimiento::where('producto_id', $productoId)
                ->select('sucursal_id')
                ->selectRaw('SUM(ingreso) - SUM(salida) as stock_sucursal')
                ->groupBy('sucursal_id')
                ->get();

                // dd($stocks);*/
            // $stocks = Sucursal::leftJoin('movimientos', function($join) use ($productoId) {
            //         $join->on('sucursales.id', '=', 'movimientos.sucursal_id')
            //             ->where('movimientos.producto_id', '=', $productoId);
            //     })
            //     ->select(
            //         'sucursales.id as sucursal_id',
            //         'sucursales.nombre as sucursal_nombre',
            //         DB::raw('COALESCE(SUM(movimientos.ingreso) - SUM(movimientos.salida), 0) as stock_sucursal')
            //     )
            //     ->groupBy('sucursales.id', 'sucursales.nombre')
            //     ->get();

            $sucursales = Sucursal::all();

            $valores = [
                'stock' => view('movimiento.ajaxListado')->with(compact('productoId', 'sucursales'))->render()
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
            $movimiento->ingreso     = $ingreso;
            $movimiento->salida      = 0;
            $movimiento->fecha       = $fecha;
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

            $stockActual = Movimiento::where('producto_id', $producto_id)
            ->where('sucursal_id', $sucursal_id)
            ->selectRaw('COALESCE(SUM(ingreso) - SUM(salida), 0) as stock')
            ->value('stock');

            // ✅ 2. Verificar si hay suficiente stock
            if ($salida > $stockActual) {
                return Respuesta::error(null, "No hay suficiente stock. Disponible: {$stockActual}");
            }

            //LA CREACION DE UN NUEVa movimiento
            $movimiento = new Movimiento();
            $movimiento->usuario_creador_id = $usuario->id;

            $movimiento->producto_id = $producto_id;
            $movimiento->sucursal_id = $sucursal_id;
            $movimiento->salida      = $salida;
            $movimiento->ingreso     = 0;
            $movimiento->fecha       = $fecha;
            $movimiento->descripcion = $descripcion;

            $movimiento->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
