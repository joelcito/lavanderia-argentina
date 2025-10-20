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
use PDF;

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

            $numeroFacturaEmpresa = $this->numeroFactura($sucursal->id);
            $numeroFacturaEmpresa = ($numeroFacturaEmpresa == null ? 1 : ($numeroFacturaEmpresa + 1));

            $factura                        = new Factura();
            $factura->usuario_creador_id    = $usuario->id;
            $factura->cliente_id            = $cliente_id;
            $factura->sucursal_id           = $sucursal->id;
            $factura->usuario_recepciono_id = $usuario_recepciono;
            $factura->fecha                 = date('Y-m-d H:i:s');
            $factura->prioridad             = $prioridad_cliente;
            $factura->fecha_recepcion       = $fecha_recepcion;
            $factura->entregado_por         = $entregado_por;
            $factura->numero_factura        = $numeroFacturaEmpresa;
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

            $factura->total       = $montoTotalVenta;
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

    public function listado(Request $request){

        return view('factura.listado');

    }

    public function ajaxListadoFacturas(Request $request)
    {
        if ($request->ajax()) {

            $usuario    = Auth::user();
            $usuario_id = $usuario->id;

            $query = Factura::select(
                'facturas.estado',
                'facturas.nit',
                'facturas.id',
                'facturas.fecha',
                'facturas.total',
                'facturas.numero_factura',
                'facturas.usuario_creador_id',
                'facturas.sucursal_id',
                'facturas.prioridad',
                'clientes.cedula',
                'clientes.nombres',
                'clientes.ap_paterno',
                'clientes.ap_materno',
            )
                ->join('clientes', 'clientes.id', '=', 'facturas.cliente_id')
                // ->where('facturas.facturado', 'No')
                // ->where('facturas.sucursal_id', $sucursal_id)
                // ->where('facturas.punto_venta_id', $punto_venta_id)
                // ->whereNull('facturas.codigo_descripcion')
                // ->whereNotNull('facturas.numero_factura')
                // ->whereNull('facturas.numero_recibo')
            ;

            // if (Auth::user()->rol_id != 1) {
            //     $query->where('facturas.sucursal_id', Auth::user()->punto_venta->sucursal->id)
            //         ->where('facturas.punto_venta_id', Auth::user()->punto_venta->id);
            // }

            if (!is_null($request->input('buscar_nro_factura'))) {
                $numero_factura = $request->input('buscar_nro_factura');
                $query->where('facturas.numero_factura', $numero_factura);
            }

            if (!is_null($request->input('buscar_nro_cedula'))) {
                $cedula = $request->input('buscar_nro_cedula');
                $query->where('clientes.cedula', $cedula);
            }

            if (!is_null($request->input('buscar_nit'))) {
                $nit = $request->input('buscar_nit');
                $query->where('facturas.nit', $nit);
            }

            if (!is_null($request->input('buscar_fecha_inicio')) && !is_null($request->input('buscar_fecha_fin'))) {
                $fecha_ini = $request->input('buscar_fecha_inicio');
                $fecha_fin = $request->input('buscar_fecha_fin');
                $query->whereBetween('facturas.fecha', [$fecha_ini . " 00:00:00", $fecha_fin . " 23:59:59"]);
            }

            if (
                !is_null($request->input('buscar_nro_factura')) &&
                !is_null($request->input('buscar_nro_cedula')) &&
                !is_null($request->input('buscar_fecha_inicio')) &&
                !is_null($request->input('buscar_fecha_fin'))
            ) {
                $facturas = $query->limit(500)->get();
            } else {
                $facturas = $query->orderBy('facturas.id', 'desc')->limit(100)->get();
                // $facturas = $query->orderBy('facturas.id', 'desc')->with('empresa')->get();
            }

            // $urlApiServicioSiat = new UrlApiServicio();
            // $UrlVerificaFactura = $urlApiServicioSiat->getUrlVerificaFactura($this->codigo_ambiente);
            // $url_verifica_factura = $UrlVerificaFactura->url_servicio;
            // $nitEmpresa = $this->nit;

            $url_verifica_factura = null;
            $nitEmpresa = null;

            $valores = [
                'listado' => view('factura.ajaxListadoFacturas')->with(compact('facturas', 'url_verifica_factura', 'nitEmpresa'))->render()
            ];
            $data = Respuesta::success($valores, "Datos obtenidos correctamente");
        } else {
            // $data['text']   = 'No existe';
            // $data['estado'] = 'error';
            $data = Respuesta::error(null, "No existe");
        }
        return $data;
    }

    public function numeroFactura($sucursal_id)
    {

        $numeroFactura = Factura::where('sucursal_id', $sucursal_id)
            ->selectRaw('MAX(CAST(numero_factura AS UNSIGNED)) as numero_factura')
            ->pluck('numero_factura')
            ->first();

        return $numeroFactura;
    }

    public function  imprimeTicked(Request $request, $factura_id)
    {
        $usuario = Auth::user();
        $factura = Factura::find($factura_id);

        $data = [
            'cliente' => 'Juan Pérez',
            'fecha'   => date('d/m/Y'),
            'monto'   => 150.50,
            'detalle' => 'Pago de hospedaje - Habitación 203',
            'usuario' => $usuario,
            'factura' => $factura
        ];

        $pdf = PDF::loadView('factura.pdf.recibo', $data)
            // ->setPaper([0, 0, 612, 396]);
            ->setPaper('a5', 'landscape');

        return $pdf->stream('recibo.pdf');

        // $factura = Factura::where('id', $factura_id)
        //     ->where('sucursal_id', $sucursal->id)
        //     ->with('sucursal')
        //     ->with('cliente')
        //     ->with('usuarioCreador')
        //     ->first();

        // if (!$factura) {
        //     return redirect()->back()->with('error', 'Factura no encontrada o no pertenece a su sucursal.');
        // }

        // // Aquí puedes agregar la lógica para generar el ticket de impresión
        // // Por ejemplo, podrías usar una librería para generar un PDF o enviar los datos a una impresora

        // return view('factura.imprimeTicket')->with(compact('factura'));

    }

}
