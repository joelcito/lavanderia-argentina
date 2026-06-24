<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Producto;
use App\Models\Tipo_proceso;
use App\Models\User;
use App\Utils\Respuesta;
use Illuminate\Http\Request;

class CotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listado()
    {
        $tipoProcesos = Tipo_proceso::all();
        $productos = Producto::with('ultimoIngreso')->get();

        return view('cotizacion.listado')->with(compact('tipoProcesos','productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function ajaxListado(Request $request)
    {
        if($request->ajax()){
            $cotizaciones = Cotizacion::all();
            $valores = [
                'listado' => view('cotizacion.ajaxListado')->with(compact('cotizaciones'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function buscarCliente(Request $request)
    {
        if($request->ajax()){
            $cedula = $request->input('cedula');
            $cliente = User::where('cedula', $cedula)->first();
            if($cliente){
                $valores = [
                    'cliente' => $cliente
                ];
                $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
            }else{
                $data = Respuesta::error(null, "No se encontro al cliente");
            }
        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    /**
     * Display the specified resource.
     */
    public function show(Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cotizacion $cotizacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cotizacion $cotizacion)
    {
        //
    }
}
