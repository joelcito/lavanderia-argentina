<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Order_trabajo;
use App\Models\Pago;
use App\Models\SolicitudDetalleProceso;
use App\Models\Sucursal;
use App\Models\User;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\UseUse;

class ReporteController extends Controller
{
    public function formulario(Request $request)
    {

        // $clientes = Cliente::all();

        $clientes = User::where('rol_id', 3)->get();
        return view('reporte.formulario')->with(compact('clientes'));

    }

    public function formularioStock()
    {
        $sucursales = DB::table('sucursales')->get();
        return view('reporte.stock_formulario', compact('sucursales'));
    }

    public function formularioProceso()
    {
        $facturas = Factura::orderBy('id', 'desc')->get();
        return view('reporte.stock_formulario_proceso', compact('facturas'));
    }

    public function formularioStockCompra()
    {
        $sucursales = DB::table('sucursales')->get();

        $codigosCompra = DB::table('movimientos')
            ->select('codigo_compra')
            ->distinct()
            ->orderBy('codigo_compra')
            ->get();

        return view('reporte.stock_formulario_compra', compact(
            'sucursales',
            'codigosCompra'
        ));
    }

    public function formularioCostos()
    {
        $solicitudes = DB::table('solicitudes')
            ->select('id')
            ->orderBy('id', 'desc')
            ->get();

        return view('reporte.estructura_costos', compact('solicitudes'));
    }

    public function obtenerOTs($factura_id)
    {
        return Order_trabajo::where('factura_id', $factura_id)
            ->select('id', 'nro_ot')
            ->orderBy('nro_ot')
            ->get();
    }

    public function procesoPdf(Request $request)
    {

        $ordenTrabajo = Order_trabajo::with([
            'prenda',
            'prelavado',
            'focalizado',
            'nevado',
            'tipoTela',
            'colorTela',
            'caracteristicaTela',
            'factura.cliente',
            'procesos.producto',
            'procesos.tipoProceso'
        ])->findOrFail($request->order_trabajo_id);

        $factura = $ordenTrabajo->factura;
        $procesos = $ordenTrabajo->procesos;

        $totalPrendas = $ordenTrabajo->cantidad;
        $totalPeso = $ordenTrabajo->peso;
        $fechaImpresion = now()->format('d/m/Y');

        return PDF::loadView('reporte.pdf.proceso_pdf', compact(
            'factura',
            'ordenTrabajo',
            'procesos',
            'totalPrendas',
            'totalPeso',
            'fechaImpresion'
        ))->stream('ficha_proceso_OT_' . $ordenTrabajo->nro_ot . '.pdf');
    }

    public function generarProcesoPDF(Request $request)
    {

        $ot = Order_trabajo::with([
            'factura.cliente',
            'detalles.prenda',
            'detalles.nombre_tela',
            'detalles.prelavado',
            'detalles.focalizado',
            'procesos.producto',
            'procesos.tipoProceso'
        ])->findOrFail($request->order_trabajo_id);

        $cliente = $ot->factura->cliente;


        $totalPrendas = $ot->detalles->sum('cantidad');
        $totalPeso = $ot->detalles->sum('peso');

        $fechaImpresion = now()->format('d/m/Y');

        $data = [
            'ot' => $ot,
            'cliente' => $cliente,
            'totalPrendas' => $totalPrendas,
            'totalPeso' => $totalPeso,
            'fechaImpresion' => $fechaImpresion
        ];

        $pdf = PDF::loadView('reporte.pdf.proceso_pdf', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->stream('ficha_proceso_OT_' . $ot->nro_ot . '.pdf');
    }

    public function cuentaPorCobrar(Request $request)
    {

        // dd($request->all());

        $cliente_id = $request->input('cliente_id');
        $usuario = Auth::user();
        // $cliente = Cliente::find($cliente_id);
        $cliente = User::find($cliente_id);

        $facturas = Factura::where('usuario_cliente_id', $cliente_id)
            ->where('estado_pago', 'DEUDA')
            ->get();

        $data = [
            'facturas' => $facturas,
            'usuario' => $usuario,
            'cliente' => $cliente

        ];

        $pdf = PDF::loadView('reporte.pdf.cuentaPorCobrar', $data)
            ->setPaper('letter', 'landscape');

        return $pdf->stream('cuentaPorCobrar.pdf');

    }

    public function cuentaPorCobrarRango(Request $request)
    {

        $nro_inicia_factura = $request->input('nro_inicia_factura');
        $nro_fin_factura    = $request->input('nro_fin_factura');
        $cliente_id         = $request->input('cliente_id_reporte_cobrar');

        $usuario = Auth::user();
        // $cliente = Cliente::find($cliente_id);
        $cliente = User::find($cliente_id);

        $facturas = Factura::where('usuario_cliente_id', $cliente_id)
                            ->where('numero_factura', '>=' ,$nro_inicia_factura)
                            ->where('numero_factura', '<=', $nro_fin_factura)
                            ->where('estado_pago', 'DEUDA')
                            ->whereNull('estado_venta')
                            ->get();

        $data = [
            'facturas' => $facturas,
            'usuario' => $usuario,
            'cliente' => $cliente

        ];

        $pdf = PDF::loadView('reporte.pdf.cuentaPorCobrar', $data)
            ->setPaper('letter', 'landscape');

        return $pdf->stream('cuentaPorCobrar.pdf');
    }

    public function stockHistorico(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();

        $productos = DB::table('productos')->get();
        $reporte = [];

        foreach ($productos as $producto) {

            $movimientos = DB::table('movimientos')
                ->where('producto_id', $producto->id)
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->select(
                    DB::raw('DATE(fecha) as fecha'),
                    DB::raw('SUM(ingreso) as ingreso'),
                    DB::raw('SUM(salida) as salida')
                )
                ->groupBy(DB::raw('DATE(fecha)'))
                ->get()
                ->keyBy('fecha');

            $stockInicial = DB::table('movimientos')
                ->where('producto_id', $producto->id)
                ->where('fecha', '<', $fechaInicio)
                ->select(DB::raw('SUM(ingreso - salida) as stock'))
                ->value('stock') ?? 0;

            $saldo = $stockInicial;
            $detalle = [];

            for ($fecha = $fechaInicio->copy(); $fecha <= $fechaFin; $fecha->addDay()) {

                $f = $fecha->format('Y-m-d');
                $ingreso = $movimientos[$f]->ingreso ?? 0;
                $salida = $movimientos[$f]->salida ?? 0;

                $detalle[] = [
                    'fecha' => $fecha->format('d-m-Y'),
                    'inicio' => round($saldo, 4),
                    'ingreso' => round($ingreso, 4),
                    'salida' => round($salida, 4),
                    'saldo' => round($saldo + $ingreso - $salida, 4),
                ];

                $saldo = $saldo + $ingreso - $salida;
            }

            $reporte[] = [
                'producto' => $producto->nombre,
                'detalle' => $detalle
            ];
        }

        return response()->json($reporte);
    }


    public function stockPdf(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();
        $sucursalId = $request->sucursal_id;

        $productos = DB::table('productos')->get();
        $reporte = [];

        foreach ($productos as $producto) {
            $movimientos = DB::table('movimientos')
                ->where('producto_id', $producto->id)
                ->where('sucursal_id', $sucursalId)
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->select(
                    DB::raw('DATE(fecha) as fecha'),
                    DB::raw('SUM(ingreso) as ingreso'),
                    DB::raw('SUM(salida) as salida')
                )
                ->groupBy(DB::raw('DATE(fecha)'))
                ->get()
                ->keyBy('fecha');

            $stockInicial = DB::table('movimientos')
                ->where('producto_id', $producto->id)
                ->where('sucursal_id', $sucursalId)
                ->where('fecha', '<', $fechaInicio)
                ->select(DB::raw('SUM(ingreso - salida) as stock'))
                ->value('stock') ?? 0;

            $saldo = $stockInicial;
            $detalle = [];

            for ($fecha = $fechaInicio->copy(); $fecha <= $fechaFin; $fecha->addDay()) {
                $f = $fecha->format('Y-m-d');
                $ingreso = $movimientos[$f]->ingreso ?? 0;
                $salida = $movimientos[$f]->salida ?? 0;

                $detalle[] = [
                    'fecha' => $fecha->format('d-m-Y'),
                    'inicio' => round($saldo, 4),
                    'ingreso' => round($ingreso, 4),
                    'salida' => round($salida, 4),
                    'saldo' => round($saldo + $ingreso - $salida, 4),
                ];

                $saldo = $saldo + $ingreso - $salida;
            }

            $reporte[] = [
                'producto' => $producto->nombre,
                'detalle' => $detalle
            ];
        }

        $pdf = Pdf::loadView('reporte.pdf.stock_pdf', compact('reporte', 'fechaInicio', 'fechaFin'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('stock_historico.pdf');
    }

    //compra
    public function reporteStockCompraPdf(Request $request)
    {
        $request->validate([
            'sucursal_id' => 'required',
            'codigo_compra' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]);

        $fechaInicio = Carbon::parse($request->fecha_inicio)->startOfDay();
        $fechaFin = Carbon::parse($request->fecha_fin)->endOfDay();
        $sucursalId = $request->sucursal_id;
        $codigoCompra = $request->codigo_compra;


        $productos = DB::table('movimientos')
            ->join('productos', 'productos.id', '=', 'movimientos.producto_id')
            ->where('movimientos.sucursal_id', $sucursalId)
            ->where('movimientos.codigo_compra', $codigoCompra)
            ->select('productos.id', 'productos.nombre')
            ->distinct()
            ->get();

        $reporte = [];

        foreach ($productos as $producto) {

            $movimientos = DB::table('movimientos')
                ->where('producto_id', $producto->id)
                ->where('sucursal_id', $sucursalId)
                ->where('codigo_compra', $codigoCompra)
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->select(
                    DB::raw('DATE(fecha) as fecha'),
                    DB::raw('SUM(ingreso) as ingreso'),
                    DB::raw('SUM(salida) as salida')
                )
                ->groupBy(DB::raw('DATE(fecha)'))
                ->orderBy('fecha')
                ->get()
                ->keyBy('fecha');


            $stockInicial = DB::table('movimientos')
                ->where('producto_id', $producto->id)
                ->where('sucursal_id', $sucursalId)
                ->where('codigo_compra', $codigoCompra)
                ->where('fecha', '<', $fechaInicio)
                ->select(DB::raw('SUM(ingreso - salida) as stock'))
                ->value('stock') ?? 0;

            $saldo = $stockInicial;
            $detalle = [];

            for ($fecha = $fechaInicio->copy(); $fecha <= $fechaFin; $fecha->addDay()) {
                $f = $fecha->format('Y-m-d');
                $ingreso = $movimientos[$f]->ingreso ?? 0;
                $salida = $movimientos[$f]->salida ?? 0;

                $detalle[] = [
                    'fecha' => $fecha->format('d/m/Y'),
                    'inicio' => round($saldo, 4),
                    'ingreso' => round($ingreso, 4),
                    'salida' => round($salida, 4),
                    'saldo' => round($saldo + $ingreso - $salida, 4),
                ];

                $saldo += $ingreso - $salida;
            }

            $reporte[] = [
                'producto' => $producto->nombre,
                'detalle' => $detalle
            ];
        }

        $sucursal = DB::table('sucursales')->where('id', $request->sucursal_id)->value('nombre');
        $codigoCompra = $request->codigo_compra;

        $pdf = Pdf::loadView('reporte.pdf.compra_pdf', compact('reporte', 'fechaInicio', 'fechaFin', 'codigoCompra', 'sucursal'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('stock_compra.pdf');
    }


    public function reporteEstructuraCostosPdf(Request $request)
    {
        $request->validate([
            'solicitud_id' => 'required',
        ]);

        $solicitud = DB::table('solicitudes')
            ->where('id', $request->solicitud_id)
            ->first();

        if (!$solicitud) {
            return back()->with('error', 'Solicitud no encontrada');
        }



        $ordenes = collect(json_decode($solicitud->ordenes_trabajo, true));

        $ots = $ordenes
            ->pluck('ots')
            ->flatten()
            ->map(fn($v) => (int) $v)
            ->unique()
            ->values();

        $facturas = $ordenes
            ->pluck('factura_id')
            ->filter()
            ->unique()
            ->values();



        $procesos = \App\Models\Proceso::with(['tipoProceso'])
            ->whereIn('order_trabajo_id', $ots)
            ->get();



        $movimientos = DB::table('movimientos')
            ->join('productos', 'productos.id', '=', 'movimientos.producto_id')
            ->whereIn('movimientos.movimiento_id', $ots) // 🔥 CLAVE
            ->where('movimientos.salida', '>', 0)
            ->select(
                'movimientos.movimiento_id',
                'movimientos.producto_id',
                'productos.nombre as producto',
                'movimientos.salida as cantidad',
                'movimientos.precio'
            )
            ->get();




        $precios = DB::table('movimientos')
            ->where('ingreso', '>', 0)
            ->whereNotNull('precio')
            ->select(
                'producto_id',
                DB::raw('AVG(precio) as precio')
            )
            ->groupBy('producto_id')
            ->get()
            ->keyBy('producto_id');



        $movimientos = $movimientos->map(function ($m) use ($precios) {

            $precio = $precios[$m->producto_id]->precio ?? 0;
            $cantidadKg = $m->cantidad / 1000;

            return [
                'movimiento_id' => (int) $m->movimiento_id,
                'producto' => $m->producto,
                'cantidad' => $m->cantidad,
                'precio' => $precio,
                'costo' => $cantidadKg * $precio,
            ];
        });



        $pagos = DB::table('pagos')
            ->whereIn('factura_id', $facturas)
            ->get();

        $sueldos = $pagos->where('tipo_pago', 'salario_produccion')->sum('monto');
        $descuentos = $pagos->where('tipo_pago', 'descuento')->sum('monto');

        $sueldosNetos = $sueldos - $descuentos;



        $costoQuimico = $movimientos->sum('costo');

        $costoTotal = $costoQuimico + $sueldosNetos;


        $cantidadProduccion = $movimientos->sum('cantidad') ?: 1;

        $costoUnitario = $costoTotal / $cantidadProduccion;

        $totalVentas = DB::table('facturas')
            ->whereIn('id', $facturas)
            ->sum('total');


        $precioUnitario = $totalVentas / ($cantidadProduccion ?: 1);

        $utilidad = $precioUnitario - $costoUnitario;

        $margen = $precioUnitario > 0
            ? ($utilidad / $precioUnitario) * 100
            : 0;

        $reporte = $procesos
            ->groupBy(fn($p) => $p->tipoProceso?->nombre ?? 'SIN PROCESO')
            ->map(function ($items, $nombreProceso) use ($movimientos) {

                $otsProceso = $items
                    ->pluck('order_trabajo_id')
                    ->map(fn($v) => (int) $v);

                $detalle = $movimientos
                    ->whereIn('movimiento_id', $otsProceso)
                    ->values();

                return [
                    'proceso' => $nombreProceso,
                    'detalle' => $detalle,
                ];
            })
            ->values();
        $pdf = Pdf::loadView('reporte.pdf.costos_pdf', [
            'reporte' => $reporte,

            'costoUnitario' => round($costoUnitario, 2),
            'sueldos' => round($sueldosNetos, 2),
            'costoTotal' => round($costoTotal, 2),
            'precio' => $precioUnitario,
            'utilidad' => round($utilidad, 2),
            'margen' => round($margen, 2) . '%',

            'solicitud' => $solicitud->id,
            'factura' => $facturas->implode(', '),
            'ots' => $ots->implode(', '),

            'fechaInicio' => now(),
            'fechaFin' => now(),
        ]);

        return $pdf->stream('estructura_costos.pdf');
    }


    //pagos

    public function reporteLavador(Request $request)
    {
        $inicio = $request->fecha_inicio;
        $fin = $request->fecha_fin;

        $usuarios = User::where('rol_id', 2)->get();
        $reporte = [];

        foreach ($usuarios as $user) {


            $asistencias = Asistencia::where('user_id', $user->id)
                ->whereBetween('fecha', [$inicio, $fin])
                ->get()
                ->groupBy('fecha');

            $totalSegundos = 0;

            foreach ($asistencias as $registros) {

                foreach ($registros as $a) {

                    if (!$a->hora_entrada || !$a->hora_salida)
                        continue;

                    $entrada = strtotime($a->hora_entrada);
                    $salida = strtotime($a->hora_salida);

                    if ($salida <= $entrada)
                        continue;

                    $totalSegundos += ($salida - $entrada);
                }
            }
            $pagoTotal = 0;

            if ($user->horas_base > 0) {
                $pagoHora = $user->pago_diario / $user->horas_base;
                //  $pagoMinuto = $pagoHora / 60;
                //  $totalMinutos = $totalSegundos / 60;
                //  $pagoTotal = $totalMinutos * $pagoMinuto;
                $pagoTotal = ($totalSegundos / 3600) * $pagoHora;
            }

            $pagos = Pago::where('user_id', $user->id)
                ->where(function ($q) use ($inicio, $fin) {
                    $q->whereBetween('fecha_inicio', [$inicio, $fin])
                        ->orWhereBetween('fecha_fin', [$inicio, $fin])
                        ->orWhere(function ($q2) use ($inicio, $fin) {
                            $q2->where('fecha_inicio', '<=', $inicio)
                                ->where('fecha_fin', '>=', $fin);
                        });
                })
                ->get();



            $adelantos = $pagos->where('tipo_pago', 'adelanto')->sum('monto');
            $descuentos = $pagos->where('tipo_pago', 'descuento')->sum('monto');
            $pagado = $pagos->where('tipo_pago', 'salario')->sum('monto');

            $totalFinal = $pagoTotal - $adelantos - $descuentos;

            $estado = $pagado > 0 ? 'PAGADO' : 'PENDIENTE';


            $reporte[] = [
                'nombres' => $user->nombres,
                'ap_paterno' => $user->ap_paterno,
                'ap_materno' => $user->ap_materno,

                'monto_semana' => $pagoTotal,
                'descuento' => $descuentos,
                'total' => $totalFinal,
                'estado' => $estado ? 'PAGADO' : ''
            ];
        }


        $pdf = PDF::loadView('personal.pdf.reporte_pago_lavador', [
            'reporte' => $reporte,
            'inicio' => $inicio,
            'fin' => $fin
        ]);

        return $pdf->stream('reporte_lavadores.pdf');
    }



    public function reporteAuxiliar(Request $request)
    {
        $inicio = $request->fecha_inicio;
        $fin = $request->fecha_fin;

        $usuarios = User::where('rol_id', 8)->get();
        $reporte = [];

        foreach ($usuarios as $user) {


            $asistencias = Asistencia::where('user_id', $user->id)
                ->whereBetween('fecha', [$inicio, $fin])
                ->get()
                ->groupBy('fecha');

            $totalSegundos = 0;

            foreach ($asistencias as $registros) {

                foreach ($registros as $a) {

                    if (!$a->hora_entrada || !$a->hora_salida)
                        continue;

                    $entrada = strtotime($a->hora_entrada);
                    $salida = strtotime($a->hora_salida);

                    if ($salida <= $entrada)
                        continue;

                    $totalSegundos += ($salida - $entrada);
                }
            }
            $pagoTotal = 0;

            if ($user->horas_base > 0) {
                $pagoHora = $user->pago_diario / $user->horas_base;
                //  $pagoMinuto = $pagoHora / 60;
                //  $totalMinutos = $totalSegundos / 60;
                //  $pagoTotal = $totalMinutos * $pagoMinuto;
                $pagoTotal = ($totalSegundos / 3600) * $pagoHora;
            }

            $pagos = Pago::where('user_id', $user->id)
                ->where(function ($q) use ($inicio, $fin) {
                    $q->whereBetween('fecha_inicio', [$inicio, $fin])
                        ->orWhereBetween('fecha_fin', [$inicio, $fin])
                        ->orWhere(function ($q2) use ($inicio, $fin) {
                            $q2->where('fecha_inicio', '<=', $inicio)
                                ->where('fecha_fin', '>=', $fin);
                        });
                })
                ->get();



            $adelantos = $pagos->where('tipo_pago', 'adelanto')->sum('monto');
            $descuentos = $pagos->where('tipo_pago', 'descuento')->sum('monto');
            $pagado = $pagos->where('tipo_pago', 'salario')->sum('monto');

            $totalFinal = $pagoTotal - $adelantos - $descuentos;

            $estado = $pagado > 0 ? 'PAGADO' : 'PENDIENTE';


            $reporte[] = [
                'nombres' => $user->nombres,
                'ap_paterno' => $user->ap_paterno,
                'ap_materno' => $user->ap_materno,

                'monto_semana' => $pagoTotal,
                'descuento' => $descuentos,
                'total' => $totalFinal,
                'estado' => $estado ? 'PAGADO' : ''
            ];
        }


        $pdf = PDF::loadView('personal.pdf.reporte_pago_auxiliar', [
            'reporte' => $reporte,
            'inicio' => $inicio,
            'fin' => $fin
        ]);

        return $pdf->stream('reporte_auxiliar.pdf');
    }








    public function reporteFocalizador(Request $request)
    {
        $inicio = $request->fecha_inicio . ' 00:00:00';
        $fin = $request->fecha_fin . ' 23:59:59';
        $inicioFormato = Carbon::parse($inicio)->format('d/m/Y');
        $finFormato = Carbon::parse($fin)->format('d/m/Y');

        $userId = $request->user_id;
        $sucursalId = $request->sucursal_id;

        $usuarios = User::where('rol_id', 6);

        if ($request->user_id) {
            $usuarios->where('id', $request->user_id);
        }

        if ($request->sucursal_id) {
            $usuarios->where('sucursal_id', $request->sucursal_id);
        }

        $usuarios = $usuarios->get();
        $reporte = [];

        foreach ($usuarios as $user) {

            $detalles = SolicitudDetalleProceso::with([
                'ordenTrabajo.prenda',
                'ordenTrabajo.tela',
                'ordenTrabajo.prelavado',
                'ordenTrabajo.focalizado',
                'ordenTrabajo.nevado',
                'ordenTrabajo.tipoTela',
                'ordenTrabajo.colorTela',
                'ordenTrabajo.caracteristicasTelas'
            ])
                ->where('tipo_proceso', 'FOCALIZADO')
                ->whereBetween('created_at', [$inicio, $fin])
                ->where('usuario_creador_id', $user->id)
                ->get();

            if ($detalles->isEmpty())
                continue;


            $pagos = Pago::where('user_id', $user->id)
                ->where(function ($q) use ($request) {
                    $q->whereBetween('fecha_inicio', [$request->fecha_inicio, $request->fecha_fin])
                        ->orWhereBetween('fecha_fin', [$request->fecha_inicio, $request->fecha_fin])
                        ->orWhere(function ($q2) use ($request) {
                            $q2->where('fecha_inicio', '<=', $request->fecha_inicio)
                                ->where('fecha_fin', '>=', $request->fecha_fin);
                        });
                })
                ->get();

            $adelantos = $pagos->where('tipo_pago', 'adelanto')->sum('monto');
            $descuentos = $pagos->where('tipo_pago', 'descuento')->sum('monto');
            $pagado = $pagos->where('tipo_pago', 'salario_produccion')->sum('monto');

            $estado = $pagado > 0 ? 'PAGADO' : 'PENDIENTE';

            $filas = [];
            $totalCantidad = 0;
            $total = 0;

            $agrupado = [];

            foreach ($detalles as $d) {

                $factura = Factura::with('cliente')->find($d->factura_id);

                $facturaNum = $factura->numero_factura ?? $d->factura_id;
                $cliente = ($factura && $factura->cliente) ? $factura->cliente->name : '—';
                $ot = $d->order_trabajo_id;

                $orden = $d->ordenTrabajo;
                $categoria = $orden
                    ? implode(' | ', array_filter([
                        optional($orden)->tela?->nombre ? 'Tela: ' . $orden->tela->nombre : null,
                        optional($orden)->prelavado?->nombre ? 'Prelavado: ' . $orden->prelavado->nombre : null,
                        optional($orden)->nevado?->nombre ? 'Nevado: ' . $orden->nevado->nombre : null,
                        optional($orden)->focalizado?->nombre ? 'Focalizado:' . $orden->focalizado->nombre : null,
                        optional($orden)->tipoTela?->nombre ? 'Tipo Tela: ' . $orden->tipoTela->nombre : null,
                        optional($orden)->colorTela?->nombre ? 'Color Tela: ' . $orden->colorTela->nombre : null,
                        optional($orden)->caracteristicasTelas?->nombre ? 'Característica: ' . $orden->caracteristicasTelas->nombre : null

                    ]))
                    : 'SIN ORDEN';




                $precio = ($factura && $factura->prioridad === 'PARA LA FERIA') ? 0.30 : 0.50;

                $key = $facturaNum . '-' . $ot;

                if (!isset($agrupado[$key])) {
                    $agrupado[$key] = [
                        'factura' => $facturaNum,
                        'cliente' => $cliente,
                        'ot' => $ot,
                        'categoria' => $categoria,
                        'cantidad' => 0,
                        'precio' => $precio,
                        'subtotal' => 0
                    ];
                }

                $agrupado[$key]['cantidad'] += $d->cantidad;
                $agrupado[$key]['subtotal'] += $d->cantidad * $precio;

                $totalCantidad += $d->cantidad;
                $total += $d->cantidad * $precio;
            }

            //$filas = array_values($agrupado);

            $reporte[] = [
                'nombre' => $user->nombres . ' ' . $user->ap_paterno . ' ' . $user->ap_materno,
                'filas' => array_values($agrupado),
                'total_cantidad' => $totalCantidad,
                'total' => $total,
                'adelantos' => $adelantos,
                'descuentos' => $descuentos,
                'pagado' => $pagado,
                'total_final' => $total - $adelantos - $descuentos,
                'estado' => $estado
            ];
        }

        $pdf = PDF::loadView('personal.pdf.reporte_pago_focalizador', compact(
            'reporte',
            'inicioFormato',
            'finFormato'
        ));

        return $pdf->stream('reporte_focalizador.pdf');
    }


    public function reportePlanchador(Request $request)
    {
        $inicio = $request->fecha_inicio . ' 00:00:00';
        $fin = $request->fecha_fin . ' 23:59:59';

        $inicioFormato = Carbon::parse($request->fecha_inicio)->format('d/m/Y');
        $finFormato = Carbon::parse($request->fecha_fin)->format('d/m/Y');

        $userId = $request->user_id;
        $sucursalId = $request->sucursal_id;

        $sucursalNombre = $sucursalId
            ? Sucursal::where('id', $sucursalId)->value('nombre')
            : 'Todas';

        $usuariosQuery = User::where('rol_id', 5);

        if ($userId) {
            $usuariosQuery->where('id', $userId);
        }

        if ($sucursalId) {
            $usuariosQuery->where('sucursal_id', $sucursalId);
        }

        $usuarios = $usuariosQuery->get();

        $reporte = [];

        foreach ($usuarios as $user) {
            $detalles = SolicitudDetalleProceso::with([
                'factura',
                'prenda',
                'ordenTrabajo.prenda',
                'ordenTrabajo.prelavado',
                'ordenTrabajo.focalizado',
                'ordenTrabajo.nevado',
                'ordenTrabajo.tipoTela',
                'ordenTrabajo.colorTela',

                'ordenTrabajo.caracteristicasTelas'
            ])
                ->where('tipo_proceso', 'PLANCHADO')
                ->where('usuario_creador_id', $user->id)
                ->whereBetween('created_at', [$inicio, $fin])
                ->get();

            if ($detalles->isEmpty())
                continue;

            $pagos = Pago::where('user_id', $user->id)
                ->whereDate('fecha_inicio', '>=', $request->fecha_inicio)
                ->whereDate('fecha_fin', '<=', $request->fecha_fin)
                ->get();

            $adelantos = $pagos->where('tipo_pago', 'adelanto')->sum('monto');
            $descuentos = $pagos->where('tipo_pago', 'descuento')->sum('monto');
            $pagado = $pagos->where('tipo_pago', 'salario_produccion')->sum('monto');

            $detalle = [];

            foreach ($detalles as $d) {

                $factura = $d->factura;

                $facturaNumero = $factura->numero_factura ?? $factura->id ?? '-';

                $orden = $d->ordenTrabajo;
                // $categoria = $orden && $orden->caracteristicasTelas
                //     ? $orden->caracteristicasTelas->nombre
                //     : 'SIN CATEGORIA';
                $categoria = $orden
                    ? implode(' | ', array_filter([
                        optional($orden)->tela?->nombre ? 'Tela: ' . $orden->tela->nombre : null,
                        optional($orden)->prelavado?->nombre ? 'Prelavado: ' . $orden->prelavado->nombre : null,
                        optional($orden)->nevado?->nombre ? 'Nevado: ' . $orden->nevado->nombre : null,
                        optional($orden)->focalizado?->nombre ? 'Focalizado:' . $orden->focalizado->nombre : null,
                        optional($orden)->tipoTela?->nombre ? 'Tipo Tela: ' . $orden->tipoTela->nombre : null,
                        optional($orden)->colorTela?->nombre ? 'Color Tela: ' . $orden->colorTela->nombre : null,
                        optional($orden)->caracteristicasTelas?->nombre ? 'Característica: ' . $orden->caracteristicasTelas->nombre : null

                    ]))
                    : 'SIN ORDEN';

                $prendaObj = $d->prenda;

                $prenda = $prendaObj->nombre ?? 'SIN PRENDA';
                $precio = $prendaObj->precio_planchado ?? 0;

                $ot = $d->order_trabajo_id;
                $cantidad = $d->cantidad;

                $totalPrenda = $cantidad * $precio;

                $detalle[] = [
                    'factura' => $facturaNumero,
                    'ot' => $ot,
                    'prenda' => $prenda,
                    'categoria' => $categoria,
                    'cantidad' => $cantidad,
                    'precio' => $precio,
                    'total' => $totalPrenda
                ];
            }

            $totalProduccion = array_sum(array_column($detalle, 'total'));

            $reporte[] = [
                'nombre' => $user->nombres . ' ' . $user->ap_paterno,
                'detalle' => $detalle,
                'total_produccion' => $totalProduccion,
                'adelantos' => $adelantos,
                'descuentos' => $descuentos,
                'pagado' => $pagado,
                'total_final' => $totalProduccion - $adelantos - $descuentos,
                'estado' => $pagado > 0 ? 'PAGADO' : 'PENDIENTE'
            ];
        }

        $pdf = PDF::loadView('personal.pdf.reporte_pago_planchador', compact(
            'reporte',
            'inicioFormato',
            'finFormato',
            'sucursalNombre'
        ));

        return $pdf->stream('reporte_planchador.pdf');
    }

    public function vistaPlanchador()
    {
        $usuarios = User::where('rol_id', 5)->get(); // planchadores
        $sucursales = Sucursal::all();

        return view('personal.personalPlanchador', compact('usuarios', 'sucursales'));
    }

}
