<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Factura;
use App\Models\Order_trabajo;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function formulario(Request $request)
    {

        $clientes = Cliente::all();

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
        $cliente = Cliente::find($cliente_id);

        $facturas = Factura::where('cliente_id', $cliente_id)
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



}
