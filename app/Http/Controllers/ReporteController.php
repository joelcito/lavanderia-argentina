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
        // Traemos OTs que tienen procesos, agrupadas por OT, ordenadas por número de OT
        $ots = DB::table('procesos')
            ->join('order_trabajos', 'procesos.order_trabajo_id', '=', 'order_trabajos.id')
            ->select('order_trabajos.id as ot_id', 'order_trabajos.nro_ot')
            ->groupBy('order_trabajos.id', 'order_trabajos.nro_ot')
            ->orderBy('order_trabajos.nro_ot', 'asc')
            ->get();

        return view('reporte.stock_formulario_proceso', compact('ots'));
    }

    // Método para generar PDF del proceso
    public function procesoPdf(Request $request)
    {
        $otId = $request->order_trabajo_id;

        $ot = Order_trabajo::with([
            'factura.cliente',       // Cliente
            'detalles.prenda',
            'detalles.nombre_tela',
            'detalles.prelavado',
            'detalles.focalizado',
            'procesos.producto',
            'procesos.tipoProceso'
        ])->findOrFail($otId);

        $cliente = $ot->factura->cliente ?? null;

        $totalPrendas = $ot->detalles->sum('cantidad');
        $totalPeso = $ot->detalles->sum('peso');
        $fechaImpresion = now()->format('d/m/Y');

        $pdf = PDF::loadView('reporte.pdf.proceso_pdf', compact(
            'ot',
            'cliente',
            'totalPrendas',
            'totalPeso',
            'fechaImpresion'
        ))->setPaper('A4', 'portrait');

        return $pdf->stream('ficha_proceso_' . $ot->nro_ot . '.pdf');
    }


    public function generarProcesoPDF(Request $request)
    {
        $fechaInicio = Carbon::parse($request->fecha_inicio);
        $fechaFin = Carbon::parse($request->fecha_fin);
        $sucursal_id = $request->sucursal_id;

        $ots = Order_trabajo::with([
            'factura.cliente',
            'detalles.prenda',
            'detalles.nombre_tela',
            'detalles.prelavado',
            'detalles.focalizado',
            'procesos.producto',
            'procesos.tipoProceso'
        ])
            ->where('sucursal_id', $sucursal_id)
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->get();

        $fechaImpresion = now()->format('d/m/Y');

        $pdf = PDF::loadView('reporte.pdf.proceso_pdf', compact('ots', 'fechaImpresion'));
        return $pdf->stream('reporte_procesos.pdf');
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




}
