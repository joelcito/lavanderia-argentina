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
                ->selectRaw('SUM(ingreso) as stock_sucursal')
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
    public function agregarIngreso(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'productoId' => 'required|integer|exists:productos,id',
                'sucursal_id' => 'required|integer|exists:sucursales,id',
                'cantidad' => 'required|numeric|min:0.01',
            ]);

            $usuario = Auth::user();

            Movimiento::create([
                'productoId' => $request->producto_id,
                'sucursal_id' => $request->sucursal_id,
                'ingreso' => $request->cantidad,
                'egreso' => 0,
                'usuario_id' => $usuario->id,
            ]);

            return Respuesta::success(null, "Ingreso registrado correctamente");
        }

        return Respuesta::error(null, "Error al registrar ingreso");
    }

    /**
     * Añadir egreso de producto de una sucursal
     */
    public function agregarEgreso(Request $request)
    {
        if ($request->ajax()) {
            $request->validate([
                'productoId' => 'required|integer|exists:productos,id',
                'sucursal_id' => 'required|integer|exists:sucursales,id',
                'cantidad' => 'required|numeric|min:0.01',
            ]);

            $usuario = Auth::user();

            Movimiento::create([
                'productoId' => $request->producto_id,
                'sucursal_id' => $request->sucursal_id,
                'ingreso' => 0,
                'egreso' => $request->cantidad,
                'usuario_id' => $usuario->id,
            ]);

            return Respuesta::success(null, "Egreso registrado correctamente");
        }

        return Respuesta::error(null, "Error al registrar egreso");
    }
}
