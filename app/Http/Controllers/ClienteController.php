<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Utils\Respuesta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function listado (){
        return view ('cliente.listado');
    }

    public function ajaxListado (Request $request){
        if($request->ajax()){
            //sacamos el listado
            $clientes = Cliente::all();
            $valores=[
                'listado' => view('cliente.ajaxListado')->with(compact('clientes'))->render()
            ];
            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");

        }
        return $data;
    }

    public function guardarCliente(Request $request){
        if ($request->ajax()) {
            $cliente_id = $request->input('id');            
            $nombre = $request->input('nombre');
            $ap_paterno = $request->input('ap_paterno');
            $ap_materno = $request->input('ap_materno');
            $cedula = $request->input('cedula');
            $celular = $request->input('celular');
            $nit = $request->input('nit');
            $razon_social = $request->input('razon_social');            
            $usuario = Auth::user();

            if ($cliente_id =='0') {
                $cliente = new Cliente();
                $cliente->usuario_creador_id = $usuario->id;
            } else {
                $cliente = Cliente::find($cliente_id);
                $cliente->usuario_modificador_id = $usuario->id;
            }

            $cliente->nombres = $nombre;
            $cliente->ap_paterno = $ap_paterno;
            $cliente->ap_materno = $ap_materno;
            $cliente->cedula = $cedula;
            $cliente->celular = $celular;
            $cliente->nit = $nit;
            $cliente->razon_social = $razon_social;        
            $cliente->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");
        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");            
        }
        
        return $data;
    }

    public function eliminarCliente(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $cliente_id = $request->input('cliente');
            $usuario = Auth::user();

            //BUSCAMOS AL CLIENTE
            $cliente = Cliente::find($cliente_id);
            $cliente->usuario_eliminador_id = $usuario->id;
            $cliente->save();

            //AHORA ELIMINAMOS
            Cliente::destroy($cliente_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{
        
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }
}
