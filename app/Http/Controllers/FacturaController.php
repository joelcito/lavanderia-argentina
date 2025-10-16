<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Models\Cliente;
use App\Models\Color_tela;
use App\Models\Focalizado;
use App\Models\Nevado;
use App\Models\Nombre_tela;
use App\Models\Prelavado;
use App\Models\Prenda;
use App\Models\Tipo_tela;
use App\Models\User;
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
}
