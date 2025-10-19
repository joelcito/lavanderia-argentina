<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Utils\Respuesta;
use Illuminate\Support\Str;
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
        $this->validate($request, [
            'imagen'=>'required|image|max:6000',
            'imagen_CI_anverso'=>'required|image|max:6000',
            'imagen_CI_reverso'=>'required|image|max:6000'
        ]);

        if ($request->ajax()) {
            $cliente_id = $request->input('id');            
            $nombre = $request->input('nombre');
            $ap_paterno = $request->input('ap_paterno');
            $ap_materno = $request->input('ap_materno');
            $cedula = $request->input('cedula');
            $celular = $request->input('celular');
            $nit = $request->input('nit');
            $razon_social = $request->input('razon_social');            
            $direccion = $request->input('direccion');            
            $nombre_referencia_1 = $request->input('nombre_referencia_1');  
            $celular_referencia_1 = $request->input('celular_referencia_1');   
            $nombre_referencia_2 = $request->input('nombre_referencia_2');  
            $celular_referencia_2 = $request->input('celular_referencia_2');   
            $nombre_referencia_3 = $request->input('nombre_referencia_3');  
            $celular_referencia_3 = $request->input('celular_referencia_3');            
            //imagenes
            if ($request->hasFile('imagen', 'imagen_CI_anverso', 'imagen_CI_reverso' )) {
                $file_imagen = $request->file('imagen');
                $imagen = time() .'_'. Str::uuid() .'.'. $file_imagen->getClientOriginalExtension();
                $file_imagen->storeAs('imagenesClientes', $imagen, 'public');

                $file_imagen_CI_anverso = $request->file('imagen_CI_anverso');
                $imagen_CI_anverso = time() .'_'. Str::uuid() .'.'. $file_imagen_CI_anverso->getClientOriginalExtension();
                $file_imagen_CI_anverso->storeAs('imagenesClientes', $imagen_CI_anverso, 'public');

                $file_imagen_CI_reverso = $request->file('imagen_CI_reverso');
                $imagen_CI_reverso = time() .'_'. Str::uuid() .'.'. $file_imagen_CI_reverso->getClientOriginalExtension();
                $file_imagen_CI_reverso->storeAs('imagenesClientes', $imagen_CI_reverso, 'public');
            }
            
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
            $cliente->direccion = $direccion;
            $cliente->imagen = $imagen;
            $cliente->imagen_CI_anverso = $imagen_CI_anverso;
            $cliente->imagen_CI_reverso = $imagen_CI_reverso;
            $cliente->nombre_referencia_1 = $nombre_referencia_1;
            $cliente->celular_referencia_1 = $celular_referencia_1;
            $cliente->nombre_referencia_2 = $nombre_referencia_2;
            $cliente->celular_referencia_2 = $celular_referencia_2;
            $cliente->nombre_referencia_3 = $nombre_referencia_3;
            $cliente->celular_referencia_3 = $celular_referencia_3;
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
