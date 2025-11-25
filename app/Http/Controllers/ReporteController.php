<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Factura;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    public function formulario(Request $request){

        $clientes = Cliente::all();

        return view('reporte.formulario')->with(compact('clientes'));

    }

    public function cuentaPorCobrar(Request $request){

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
}
