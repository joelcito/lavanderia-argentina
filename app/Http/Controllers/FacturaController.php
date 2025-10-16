<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Models\Cliente;
use App\Models\Color_tela;
use App\Models\Factura;
use App\Models\Focalizado;
use App\Models\Nevado;
use App\Models\Nombre_tela;
use App\Models\Prelavado;
use App\Models\Prenda;
use App\Models\Tipo_tela;
use App\Models\User;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacturaController extends Controller
{
    public function formulario()
    {
        $clientes = Cliente::select('id', 'nombres', 'ap_paterno', 'ap_materno', 'celular','direccion','imagen')->get();
        $usuario = Auth::user();
        $usuarios = User::all();

        $prendas = Prenda::all();
        $telas = Nombre_tela::all();
        $prelavados = Prelavado::all();
        $nevados = Nevado::all();
        $focalizados = Focalizado::all();
        $tipoTelas = Tipo_tela::all();
        $colorTelas = Color_tela::all();
        $caracteristicaTelas = Caracteristica::all();

        return view('factura.formulario')->with(compact('clientes', 'usuario', 'usuarios', 'prendas', 'prelavados', 'nevados', 'focalizados', 'tipoTelas', 'colorTelas','caracteristicaTelas', 'telas'));
    }

    public function recepcionar(Request $request){

        if($request->ajax()){

            dd($request->all());
            $usuario            = Auth::user();
            $cliente_id         = $request->input('cliente');
            $prioridad_cliente  = $request->input('prioridad_cliente');
            $fecha_recepcion    = $request->input('fecha_recepcion');
            $entregado_por      = $request->input('entregado_por');
            $usuario_recepciono = $request->input('usuario_recepciono');
            $carro              = $request->input('carro');

            // $factura = new Factura();
            // $factura->usuario_creador_id = $usuario->id;
            // $factura->save();

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
        
    }

}
