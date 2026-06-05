<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Models\Color_tela;
use App\Models\Factura;
use App\Models\Focalizado;
use App\Models\Nevado;
use App\Models\Nombre_tela;
use App\Models\Order_trabajo;
use App\Models\Prelavado;
use App\Models\Prenda;
use App\Models\Proceso;
use App\Models\Solicitud;
use App\Models\Tipo_tela;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use Svg\Tag\Rect;

class OrdenTrabajoController extends Controller
{
    public function ajaxListadoOrdenTrabajos(Request $request)
    {
        if ($request->ajax()) {

            $factura_id = $request->input('factura');


            $ordem_trabajos = Order_trabajo::with('factura') // 👈 CLAVE
                ->where('factura_id', $factura_id)
                ->where('tipo', 'ORDEN_TRABAJO')
                ->get();

            $valores = [
                'listado' => view('ordenTrabajo.ajaxListadoOrdenTrabajos')->with(compact('ordem_trabajos'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function ajaxListadoOrdenTrabajosCliente(Request $request)
    {
        if ($request->ajax()) {

            $factura_id = $request->input('factura');

            $ordem_trabajos = Order_trabajo::with('factura')
                ->where('factura_id', $factura_id)
                ->where('tipo', 'ORDEN_TRABAJO')
                ->get();


            foreach ($ordem_trabajos as $ot) {

                $ot->ultimo_proceso = 'SIN PROCESO';

                $solicitudes = Solicitud::get()->filter(function ($s) use ($ot) {

                    if (!$s->ordenes_trabajo)
                        return false;

                    foreach ($s->ordenes_trabajo as $orden) {
                        if (in_array($ot->id, $orden['ots'] ?? [])) {
                            return true;
                        }
                    }

                    return false;
                });

                if ($solicitudes->count()) {

                    $solicitudesIds = $solicitudes->pluck('id');

                    $proceso = Proceso::with('tipoProceso')
                        ->whereIn('solicitud_id', $solicitudesIds)
                        ->orderByDesc('created_at')
                        ->first();

                    if ($proceso) {
                        $ot->ultimo_proceso = $proceso->tipoProceso->nombre ?? 'SIN NOMBRE';
                    }
                }
            }


            $valores = [
                'listado' => view('ordenTrabajo.ajaxListadoOrdenTrabajosCliente')->with(compact('ordem_trabajos'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function ajaxListadoOjales(Request $request)
    {
        if ($request->ajax()) {

            $factura_id = $request->input('factura');
            $orden_trabajo_id = $request->input('ot');

            $ordem_trabajos = Order_trabajo::where('factura_id', $factura_id)
                ->where('order_trabajos_id', $orden_trabajo_id)
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

    public function ajaxListadoLaser(Request $request)
    {
        if ($request->ajax()) {

            // dd($request->all());
            $factura_id = $request->input('factura');
            $orden_trabajo_id = $request->input('ot');

            // SACAMOS EL ORDEN DE TRABAJO
            $ordenTrabajo = Order_trabajo::find($orden_trabajo_id);

            // SCAMOS TODOS LOS LASER QUE PERTENENCEN A ESA VENTA Y ESE MISMO NUMERO DE OT
            $ordem_trabajos = Order_trabajo::where('factura_id', $factura_id)
                ->where('tipo', 'LASER')
                ->whereJsonContains('orden_trabajos', (int) $orden_trabajo_id)
                ->get();
            // ->toSql();

            // dd($ordem_trabajos, $factura_id, 'LASER', $orden_trabajo_id);

            $valores = [
                'listado' => view('ordenTrabajo.ajaxListadoLaser')->with(compact('ordem_trabajos'))->render()
            ];
            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarLaser(Request $request)
    {

        if ($request->ajax()) {

            // dd($request->all());

            $orden_trabajo_id             = $request->input('orden_trabajo_id');
            $numero_orden_trabajo         = $request->input('numero_orden_trabajo');
            $numero_prendas_orden_trabajo = $request->input('numero_prendas_orden_trabajo');
            $observacion_orden_trabajo    = $request->input('observacion_orden_trabajo');
            $talla_laser                  = $request->input('talla_laser');
            $cantidad_laser               = $request->input('cantidad_laser');
            $intensidad_laser             = $request->input('intensidad_laser');
            $altura_laser                 = $request->input('altura_laser');
            $dpi_laser                    = $request->input('dpi_laser');
            $pos_1_laser                  = $request->input('pos_1_laser');
            $pos_2_laser                  = $request->input('pos_2_laser');
            $pos_3_laser                  = $request->input('pos_3_laser');
            $pos_4_laser                  = $request->input('pos_4_laser');
            $prenda_x_mesa_laser          = $request->input('prenda_x_mesa_laser');
            $tiempo_total_laser           = $request->input('tiempo_total_laser');
            $usuario                      = Auth::user();
            $ordenTrabajoPadre            = Order_trabajo::find($orden_trabajo_id);
            $valor_laser                  = $request->input('valor_laser');
            $precio_minuto_valor          = $request->input('precio_minuto_valor');
            $precio_pronosticado          = $request->input('precio_pronosticado');
            $precio_cliente               = $request->input('precio_cliente');
            $totalAdicionLaserFactura     = 0;
            $factura                      = $ordenTrabajoPadre->factura;

            $ordenenesTrabajosIds = Order_trabajo::where('nro_ot', $ordenTrabajoPadre->nro_ot)
                ->where('factura_id', $factura->id)
                ->get()
                ->pluck('id');

            // dd($cantidad_laser);

            foreach ($talla_laser as $index => $value) {
                $orden_trabajo = new Order_trabajo();
                $orden_trabajo->usuario_creador_id = $usuario->id;
                $orden_trabajo->order_trabajos_id = $ordenTrabajoPadre->id;
                $orden_trabajo->factura_id = $ordenTrabajoPadre->factura_id;
                $orden_trabajo->sucursal_id = $ordenTrabajoPadre->sucursal_id;

                $orden_trabajo->talla = $talla_laser[$index];
                $orden_trabajo->cantidad = $cantidad_laser[$index];
                $orden_trabajo->intensidad = $intensidad_laser[$index];
                $orden_trabajo->altura = $altura_laser[$index];
                $orden_trabajo->dpi = $dpi_laser[$index];
                $orden_trabajo->posicion_1 = $pos_1_laser[$index];
                $orden_trabajo->posicion_2 = $pos_2_laser[$index];
                $orden_trabajo->posicion_3 = $pos_3_laser[$index];
                $orden_trabajo->posicion_4 = $pos_4_laser[$index];
                $orden_trabajo->nro_prenda_mesa = $prenda_x_mesa_laser[$index];
                $orden_trabajo->tiempo = $tiempo_total_laser[$index];
                $orden_trabajo->precio_pronosticado = $precio_pronosticado[$index];
                $orden_trabajo->precio_minuto = $precio_minuto_valor[$index];
                $orden_trabajo->precio = $precio_cliente[$index];
                $orden_trabajo->subtotal = $valor_laser[$index];
                $orden_trabajo->orden_trabajos = $ordenenesTrabajosIds;

                $orden_trabajo->fecha = date('Y-m-d H:i:s');
                $orden_trabajo->observacion = "SERVICIO DE LASER";
                $orden_trabajo->tipo = "LASER";
                $orden_trabajo->save();

                $totalAdicionLaserFactura = $totalAdicionLaserFactura + $valor_laser[$index];
            }

            $precioFactura = $factura->total;
            $factura->total = $precioFactura + $totalAdicionLaserFactura;
            $factura->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");
        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function ajaxFormularioEditarOrdenTrabajo(Request $request)
    {
        if ($request->ajax()) {

            // dd($request->all());

            $factura_id = $request->input('factura');

            $ordenesTrabajos = Order_trabajo::where('factura_id', $factura_id)
                                            ->where('tipo', 'ORDEN_TRABAJO')
                                            ->get();

            $prendas             = Prenda::all();
            $telas               = Nombre_tela::all();
            $prelavados          = Prelavado::all();
            $nevados             = Nevado::all();
            $focalizados         = Focalizado::all();
            $tipoTelas           = Tipo_tela::all();
            $colorTelas          = Color_tela::all();
            $caracteristicaTelas = Caracteristica::all();

            $valores = [
                'listado' => view('factura.ajaxFormularioEditarOrdenTrabajo')->with(compact('ordenesTrabajos', 'prendas', 'telas', 'prelavados', 'nevados', 'focalizados', 'tipoTelas', 'colorTelas', 'caracteristicaTelas', 'factura_id'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }

    public function ajaxNroOtFactura(Request $request)
    {

        if ($request->ajax()) {

            // dd($request->all());

            $factura_id = $request->input('factura');

            $ordenes = Order_trabajo::select('nro_ot')
                ->where('factura_id', $factura_id)
                ->where('tipo', 'ORDEN_TRABAJO')
                ->distinct()
                ->get();

            $valores = [
                'listaOt' => $ordenes
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }

    public function imprimirOrdenTrabajo(Request $request, $factura_id, $nro_orden)
    {

        // dd($factura_id, $nro_orden);

        $usuario = Auth::user();
        $factura = Factura::find($factura_id);
        $ordenesTrabajos = Order_trabajo::where('factura_id', $factura_id)
            ->where('nro_ot', $nro_orden)
            ->get();

        $data = [
            'cliente' => 'Juan Pérez',
            'fecha' => date('d/m/Y'),
            'monto' => 150.50,
            'detalle' => 'Pago de hospedaje - Habitación 203',
            'usuario' => $usuario,
            'factura' => $factura,
            'ordenesTrabajos' => $ordenesTrabajos,
            'nro_orden' => $nro_orden
        ];

        $pdf = PDF::loadView('ordenTrabajo.pdf.imprimirOrdenTrabajo', $data)
            // ->setPaper([0, 0, 612, 396]);
            // ->setPaper('a5', 'landscape');
            ->setPaper([0, 0, 612, 1008], 'portrait');

        return $pdf->stream('imprimirOrdenTrabajo.pdf');

    }

    public function guardarEstadoOrdenTrabajo(Request $request)
    {

        if ($request->ajax()) {

            $factura_id = $request->input('factura_id_estado');
            $estado = $request->input('estado_orden_trabajo');
            $nro_ot = $request->input('nro_ot_estado');

            Order_trabajo::where('factura_id', $factura_id)
                ->where('nro_ot', $nro_ot)
                ->update([
                    'estado' => $estado
                ]);

            $data = Respuesta::success(null, "Datos Actualizados correctamente");

        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }


    //planchado focalizado

    public function vistaRol()
    {
        $facturas = Factura::orderBy('id', 'desc')->get();


        $userRol = auth()->user()->rol->nombre ?? null;

        return view('ordenTrabajo.rol', compact('facturas', 'userRol'));
    }

    public function obtenerOTs($factura_id)
    {
        return Order_trabajo::where('factura_id', $factura_id)
            ->where('tipo', 'ORDEN_TRABAJO')
            ->select('id', 'nro_ot')
            ->orderBy('nro_ot')
            ->get();
    }


    public function guardarCantidad(Request $request)
    {
        $request->validate([
            'order_trabajo_id' => 'required|exists:order_trabajos,id',
            'tipo' => 'required|in:planchado,focalizado',
            'cantidad' => 'required|numeric|min:0'
        ]);

        $orden = Order_trabajo::findOrFail($request->order_trabajo_id);

        if ($request->tipo === 'planchado') {
            $orden->cantidad_planchado = $request->cantidad;
        } else {
            $orden->cantidad_focalizado = $request->cantidad;
        }

        $orden->save();

        return response()->json(['success' => true, 'message' => 'Cantidad guardada correctamente']);
    }

    public function listarCantidades()
    {
        $ordenes = Order_trabajo::with('factura')
            ->where('tipo', 'ORDEN_TRABAJO')
            ->get();
        $data = $ordenes->map(function ($o) {
            return [
                'factura_numero' => $o->factura->numero_factura,
                'nro_ot' => $o->nro_ot,
                'cantidad_planchado' => $o->cantidad_planchado,
                'cantidad_focalizado' => $o->cantidad_focalizado,
            ];
        });
        return response()->json($data);
    }

    public function cambiaDatoOrdenTrabajo(Request $request)
    {

        if ($request->ajax()) {

            // dd($request->all());

            $tipo          = $request->input('tipo');
            $ordenTrabajo  = $request->input('ordenTrabajo');
            $dato          = $request->input('dato');
            $orden_trabajo = Order_trabajo::find($ordenTrabajo);
            $usuario       = Auth::user();
            $sucursal      = $usuario->sucursal;

            if ($tipo == "CANTIDAD")
                $orden_trabajo->cantidad = $dato;
            elseif ($tipo == "PRENDA")
                $orden_trabajo->prenda_id = $dato;
            elseif ($tipo == "OJAL") {

                // SACAMOS EL OJAL
                $ojal = Order_trabajo::where('order_trabajos_id', $orden_trabajo->id)->first();
                $factura = $orden_trabajo->factura;

                // SACAMOS LA FACTURA
                $precioActualFactura = $factura->total;

                if ($dato > 1) {
                    if ($ojal) {
                        $precioFacturaSinOjal = $precioActualFactura - $ojal->subtotal;

                        // RECALUCLAMOS TODOD
                        $nroOjales = ($dato - 1) * $orden_trabajo->cantidad;
                        $precioOjal = $nroOjales * $ojal->precio;

                        $ojal->cantidad = $nroOjales;
                        $ojal->subtotal = $precioOjal;
                        $ojal->save();

                        // MODIFICAMOS EL PRECIO DE LA FACTURA
                        $factura->total = $precioFacturaSinOjal + $ojal->subtotal;
                        $factura->save();
                    } else {

                        // RECALUCLAMOS TODOD
                        $nroOjales = ($dato - 1) * $orden_trabajo->cantidad;
                        $precioOjal = $nroOjales * 0.33;

                        $orden_trabajoOjal                     = new Order_trabajo();
                        $orden_trabajoOjal->usuario_creador_id = $usuario->id;
                        $orden_trabajoOjal->order_trabajos_id  = $orden_trabajo->id;
                        $orden_trabajoOjal->factura_id         = $factura->id;
                        $orden_trabajoOjal->sucursal_id        = $sucursal->id;
                        $orden_trabajoOjal->cantidad           = $nroOjales;
                        $orden_trabajoOjal->precio             = 0.33;
                        $orden_trabajoOjal->subtotal           = $precioOjal;
                        $orden_trabajoOjal->observacion        = "SERVICIO DE OJAL";
                        $orden_trabajoOjal->fecha              = date('Y-m-d H:i:s');
                        $orden_trabajoOjal->tipo               = "OJAL";
                        $orden_trabajoOjal->save();
                        // PARA ADICIONAR EL PRECIO A LA FACTURA
                        $factura->total = $factura->total + $precioOjal;
                    }
                } else {
                    if ($ojal) {
                        $precioFacturaSinOjal = $precioActualFactura - $ojal->subtotal;
                        $ojal->usuario_eliminador_id = $usuario->id;
                        $ojal->save();
                        Order_trabajo::destroy($ojal->id);
                        $factura->total = $precioFacturaSinOjal;

                    }
                }
                $factura->save();
                $orden_trabajo->numero_ojales = $dato;
            } elseif ($tipo == "TELA")
                $orden_trabajo->tela_id = $dato;
            elseif ($tipo == "PRE_LAVADO")
                $orden_trabajo->prelavado_id = $dato;
            elseif ($tipo == "NEVADO")
                $orden_trabajo->nevado_id = $dato;
            elseif ($tipo == "FOCALIZADO")
                $orden_trabajo->focalizado_id = $dato;
            elseif ($tipo == "TIPO_TELA")
                $orden_trabajo->tipo_tela_id = $dato;
            elseif ($tipo == "COLOR_TELA")
                $orden_trabajo->color_tela_id = $dato;
            elseif ($tipo == "CARACTERISTICA_TELA")
                $orden_trabajo->caracteristica_tela_id = $dato;
            elseif ($tipo == "PESO")
                $orden_trabajo->peso = $dato;
            elseif ($tipo == "PRECIO") {

                $subTotal = $orden_trabajo->cantidad * $dato;
                $precioActualOrdenTrabajo = $orden_trabajo->subtotal;
                $factura = $orden_trabajo->factura;

                $orden_trabajo->precio = $dato;
                $orden_trabajo->subtotal = $subTotal;

                // PARA LA FACTURA
                $precioActualFactura = $factura->total;

                $factura = $orden_trabajo->factura;
                $factura->total = ($precioActualFactura - $precioActualOrdenTrabajo) + $subTotal;
                $factura->save();

            } elseif ($tipo == "OBSERVACIONES")
                $orden_trabajo->observacion = $dato;
            elseif ($tipo == "NRO_OT")
                $orden_trabajo->nro_ot = $dato;

            $orden_trabajo->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function ajaxFormularioLaser(Request $request)
    {

        if ($request->ajax()) {

            // dd($request->all());

            $factura = $request->input('factura');
            $ordenTrabajo = $request->input('ordenTrabajo');
            $nroOt = $request->input('nroOt');
            $observacion = $request->input('observacion');
            $cantidad = $request->input('cantidad');

            $ordenesTrabajos = Order_trabajo::where('factura_id', $factura)
                ->where('nro_ot', $nroOt)
                ->get();

            $prandas = "";

            foreach ($ordenesTrabajos as $key => $ordenTrabajo) {
                $prandas = $prandas . $ordenTrabajo->cantidad . " - " . $ordenTrabajo->prenda?->no0mbre;
            }

            $valores = [
                'formulario' => view('ordenTrabajo.formularioLaser')->with(compact('factura', 'ordenTrabajo', 'nroOt', 'observacion', 'cantidad', 'ordenesTrabajos', 'prandas'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        } else {

            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }

    public function editarLaser(Request $request){

        if($request->ajax()){

            // dd($request->all());

            $modificar_talla_laser         = $request->input('modificar_talla_laser');
            $modificar_cantidad_laser      = $request->input('modificar_cantidad_laser');
            $modificar_intensidad_laser    = $request->input('modificar_intensidad_laser');
            $modificar_altura_laser        = $request->input('modificar_altura_laser');
            $modificar_dpi_laser           = $request->input('modificar_dpi_laser');
            $modificar_pos_1_laser         = $request->input('modificar_pos_1_laser');
            $modificar_pos_2_laser         = $request->input('modificar_pos_2_laser');
            $modificar_pos_3_laser         = $request->input('modificar_pos_3_laser');
            $modificar_pos_4_laser         = $request->input('modificar_pos_4_laser');
            $modificar_prenda_x_mesa_laser = $request->input('modificar_prenda_x_mesa_laser');
            $modificar_tiempo_total_laser  = $request->input('modificar_tiempo_total_laser');
            $modificar_precio_pronosticado = $request->input('modificar_precio_pronosticado');
            $modificar_precio_minuto_valor = $request->input('modificar_precio_minuto_valor');
            $modificar_precio_cliente      = $request->input('modificar_precio_cliente');
            $modificar_valor_laser         = $request->input('modificar_valor_laser');
            $orden_trabajo_id              = $request->input('orden');
            $usuario                       = Auth::user();

            $orden                         = Order_trabajo::find($orden_trabajo_id);
            $orden->usuario_modificador_id = $usuario->id;
            $orden->cantidad               = $modificar_cantidad_laser;
            $orden->precio                 = $modificar_precio_cliente;
            $orden->precio_minuto          = $modificar_precio_minuto_valor;
            $orden->subtotal               = $modificar_valor_laser;
            $orden->posicion_1             = $modificar_pos_1_laser;
            $orden->posicion_2             = $modificar_pos_2_laser;
            $orden->posicion_3             = $modificar_pos_3_laser;
            $orden->posicion_4             = $modificar_pos_4_laser;
            $orden->nro_prenda_mesa        = $modificar_prenda_x_mesa_laser;
            $orden->intensidad             = $modificar_intensidad_laser;
            $orden->tiempo                 = $modificar_tiempo_total_laser;
            $orden->altura                 = $modificar_altura_laser;
            $orden->dpi                    = $modificar_dpi_laser;
            $orden->precio_pronosticado    = $modificar_precio_pronosticado;
            $orden->talla                  = $modificar_talla_laser;
            $orden->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }

    public function eliminarLaser(Request $request){
        if($request->ajax()){

            $orden_trabajo_id = $request->input('dato');
            $usuario          = Auth::user();

            $orden_trabajo                        = Order_trabajo::find($orden_trabajo_id);
            $orden_trabajo->usuario_eliminador_id = $usuario->id;
            $orden_trabajo->save();

            $factura        = $orden_trabajo->factura;
            $precioFactura  = $factura->total;
            $factura->total = $precioFactura - $orden_trabajo->subtotal;
            $factura->save();

            $orden_trabajo->delete();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }
}
