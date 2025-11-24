<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function formulario(Request $request){

        $clientes = Cliente::all();

        return view('reporte.formulario')->with(compact('clientes'));

    }

    public function cuentaPorCobrar(Request $request){

        dd($request->all());

    }
}
