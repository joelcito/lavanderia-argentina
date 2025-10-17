<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Models\Cliente;
use App\Models\Color_tela;
use App\Models\Factura;
use App\Models\Focalizado;
use App\Models\Nevado;
use App\Models\Nombre_tela;
use App\Models\Order_trabajo;
use App\Models\Pago;
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

            // dd($request->all());
            $usuario                   = Auth::user();
            $sucursal                  = $usuario->sucursal;
            $cliente_id                = $request->input('cliente');
            $prioridad_cliente         = $request->input('prioridad_cliente');
            $entregado_por             = $request->input('entregado_por');
            $usuario_recepciono        = $request->input('usuario_recepciono');
            $carro                     = $request->input('carro');
            $fecha_recepcion           = $request->input('fecha_recepcion');
            $realizo_pago_recibo       = $request->input('realizo_pago_recibo');
            $monto_total_pagado_recibo = $request->input('monto_total_pagado_recibo');
            $monto_pagado_recibo       = $request->input('monto_pagado_recibo');
            $cambio_pagado_recibo      = $request->input('cambio_pagado_recibo');
            $tipo_pago_pagado_recibo   = $request->input('tipo_pago_pagado_recibo');

            $factura                        = new Factura();
            $factura->usuario_creador_id    = $usuario->id;
            $factura->cliente_id            = $cliente_id;
            $factura->usuario_recepciono_id = $usuario_recepciono;
            $factura->fecha                 = date('Y-m-d H:i:s');
            $factura->prioridad             = $prioridad_cliente;
            $factura->fecha_recepcion       = $fecha_recepcion;
            $factura->entregado_por         = $entregado_por;

            $factura->save();

            $montoTotalVenta = 0;

            // AHORA VAMOS POR EL CARRO
            foreach ($carro as $key => $item) {
                $orden_trabajo                         = new Order_trabajo();
                $orden_trabajo->usuario_creador_id     = $usuario->id;
                $orden_trabajo->factura_id             = $factura->id;
                $orden_trabajo->sucursal_id            = $sucursal->id;
                $orden_trabajo->cantidad               = $item['cantidad_venta'];
                $orden_trabajo->prenda_id              = $item['prenda_id'];
                $orden_trabajo->numero_ojales          = $item['numero_ojales'];
                $orden_trabajo->tela_id                = $item['tela_id'];
                $orden_trabajo->prelavado_id           = $item['prelavado_id'];
                $orden_trabajo->nevado_id              = $item['nevado_id'];
                $orden_trabajo->focalizado_id          = $item['focalizado_id'];
                $orden_trabajo->tipo_tela_id           = $item['tipo_tela_id'];
                $orden_trabajo->color_tela_id          = $item['color_tela_id'];
                $orden_trabajo->caracteristica_tela_id = $item['caracteristica_tela_id'];
                $orden_trabajo->peso                   = $item['peso'];
                $orden_trabajo->precio                 = $item['precio_venta'];
                $orden_trabajo->subtotal               = $item['sub_total'];
                $orden_trabajo->observacion            = $item['observacion'];
                $orden_trabajo->nro_ot                 = $item['nro_ot'];
                $orden_trabajo->fecha                  = $factura->fecha_recepcion;
                $orden_trabajo->save();

                $montoTotalVenta = $montoTotalVenta + $item['sub_total'];
            }

            $factura->total       = $monto_total_pagado_recibo;
            $factura->estado_pago = ($montoTotalVenta == ($monto_pagado_recibo - $cambio_pagado_recibo))? 'PAGADO' : 'DEUDA';
            $factura->save();

            // PARA LE PAGO AQUI VA
            if($realizo_pago_recibo == "true"){

                $pago                     = new Pago();
                $pago->usuario_creador_id = $usuario->id;
                $pago->factura_id         = $factura->id;
                $pago->sucursal_id        = $sucursal->id;
                $pago->monto              = $monto_pagado_recibo - $cambio_pagado_recibo;
                $pago->cambio             = $cambio_pagado_recibo;
                $pago->fecha              = date('Y-m-d H:i:s');
                $pago->cambio             = $cambio_pagado_recibo;
                $pago->descripcion        = 'VENTA';
                $pago->tipo_pago          = $tipo_pago_pagado_recibo;
                $pago->save();

            }

            $data = Respuesta::success(null, "Datos obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }

}
