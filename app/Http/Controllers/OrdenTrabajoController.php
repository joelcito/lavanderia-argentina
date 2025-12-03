<?php

namespace App\Http\Controllers;

use App\Models\Order_trabajo;
use App\Utils\Respuesta;
use Illuminate\Http\Request;

class OrdenTrabajoController extends Controller
{
    public function ajaxListadoOrdenTrabajos(Request $request)
    {
        if($request->ajax()){

            $factura_id = $request->input('factura');

            $ordem_trabajos = Order_trabajo::where('factura_id', $factura_id)
                                ->where('tipo', 'ORDEN_TRABAJO')
                                ->get();

            $valores = [
                'listado' => view('ordenTrabajo.ajaxListadoOrdenTrabajos')->with(compact('ordem_trabajos'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function ajaxListadoOjales(Request $request) {
        if ($request->ajax()) {

            $factura_id = $request->input('factura');

            $ordem_trabajos = Order_trabajo::where('factura_id', $factura_id)
                ->where('tipo', 'OJAL')
                ->get();

            $valores = [
                'listado' => view('ordenTrabajo.ajaxListadoOjales')->with(compact('ordem_trabajos'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function ajaxListadoLaser(Request $request) {
        if ($request->ajax()) {
            $factura_id = $request->input('factura');
            $ordem_trabajos = Order_trabajo::where('factura_id', $factura_id)
                                            ->where('tipo', 'LASER')
                                            ->get();
            $valores = [
                'listado' => view('ordenTrabajo.ajaxListadoLaser')->with(compact('ordem_trabajos'))->render()
            ];
            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }
}
