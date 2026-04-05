<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Factura;
use App\Models\Pago;
use App\Models\SubCategoria;
use App\Models\Sucursal;
use App\Models\User;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public function listado(Request $request)
    {

        $usuario    = Auth::user();
        $sucursal   = $usuario->sucursal;

        $fechaIni   = date('Y-m-d');
        $fechaFin   = date('Y-m-d');

        if ($usuario->isAdmin()) {
            $usuarios   = User::all();
            $sucursales = Sucursal::all();
        } else {
            $usuarios   = User::where('id', $usuario->id)->get();
            $sucursales = Sucursal::where('id', $sucursal->id)->get();
        }

        $categoriasIngreso = Categoria::where('tipo', 'INGRESO')
                                        ->where('estado', 'PAGO')
                                        ->get();

        $categoriasSalida  = Categoria::where('tipo', 'SALIDA')
                                        ->where('estado', 'PAGO')
                                        ->get();

        $subCategorias     = SubCategoria::all();

        return view('pago.listado')->with(compact('sucursales', 'fechaIni', 'fechaFin', 'usuarios','usuario', 'categoriasIngreso', 'categoriasSalida', 'subCategorias'));
    }

    public function ajaxListado(Request $request)
    {
        if ($request->ajax()) {

            $sucursal_id = $request->input('sucursal_id');
            $fecha_ini   = $request->input('fecha_ini');
            $fecha_fin   = $request->input('fecha_fin');
            $usuario_id  = $request->input('usuario_busqueda_id');

            $query = Pago::select();

            if ($sucursal_id != null) {
                $sucursal      = Sucursal::find($sucursal_id);
                $puntoVentasId = $sucursal->puntoVentas->pluck('id')->toArray();
                $query->whereIn('punto_venta_id', $puntoVentasId);
            }

            if ($fecha_ini != null && $fecha_fin != null) {
                $query->where('fecha', '>=', $fecha_ini . ' 00:00:00')
                    ->where('fecha', '<=', $fecha_fin . ' 23:59:59');
            }

            if ($usuario_id != null) {
                $query->where('usuario_creador_id', $usuario_id);
            }

            $pagos = $query->orderBy('id', 'desc')->get();
            $valores = [
                'listado' => view('pago.ajaxListado')->with(compact('pagos'))->render()
            ];
            $data = Respuesta::success($valores, "Datos obtenidos correctamente");
        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function listadoDeuda()
    {

        $usuario = Auth::user();
        $sucursal   = $usuario->sucursal;

        return view('pago.listadoDeuda')->with(compact('usuario'));
    }

    public function ajaxListadoDeuda(Request $request)
    {
        if ($request->ajax()) {
            $facturas = Factura::with(['cliente', 'sucursal'])->where('estado_pago', 'DEUDA')->orderBy('id', 'desc')->get();

            $valores = [
                'listado' => view('pago.ajaxListadoDeuda')->with(compact('facturas'))->render()
            ];
            $data = Respuesta::success($valores, "Datos obtenidos correctamente");
        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function ajaxFormPagoDeuda(Request $request)
    {
        if ($request->ajax()) {
            $factura_id = $request->input('factura_id');

            $factura = Factura::with(['cliente', 'sucursal'])->where('id', $factura_id)->first();
            $pagos = pago::where('factura_id', $factura_id)
                ->where('estado', 'INGRESO')
                ->get();
            $pagado = pago::where('factura_id', $factura_id)
                ->where('estado', 'INGRESO')
                ->sum('monto');

            $valores = [
                'formulario' => view('pago.ajaxFormPagoDeuda')->with(compact('factura', 'pagos', 'pagado'))->render()
            ];
            $data = Respuesta::success($valores, "Datos obtenidos correctamente");
        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarPagoDeuda(Request $request)
    {
        if ($request->ajax()) {

            $request->validate([
                'factura_id' => 'required',
                'tipo_pago' => 'required',
                'importe_pago' => 'required',
                'saldo' => 'required',
            ]);

            $factura_id   = $request->input('factura_id');
            $tipo_pago    = $request->input('tipo_pago');
            $importe_pago = $request->input('importe_pago');
            $saldo        = $request->input('saldo');
            $usuario      = Auth::user();
            $sucursal     = $usuario->sucursal;

            if ($importe_pago > 0 && $importe_pago <= $saldo) {
                $nuevo                     = new pago();
                $nuevo->usuario_creador_id = $usuario->id;
                $nuevo->factura_id         = $factura_id;
                $nuevo->sucursal_id     = $sucursal->id;
                $nuevo->monto              = $importe_pago;
                $nuevo->cambio             = 0;
                $nuevo->fecha              = date('Y-m-d H:i:s');
                $nuevo->descripcion        = 'VENTA';
                $nuevo->tipo_pago          = $tipo_pago;
                $nuevo->estado             = 'INGRESO';

                $nuevo->save();

                if (($saldo - $importe_pago) == 0) {
                    $factura = Factura::find($factura_id);
                    $factura->estado_pago = 'PAGADO';
                    $factura->save();
                }

                $data = Respuesta::success(null, "Datos obtenidos correctamente");
            } else {
                $data = Respuesta::error(null, "El importe debe ser mayor a 0 y menor al saldo.");
            }
        } else {
            $data = Respuesta::error(null, "Error en registro de datos.");
        }
        return $data;
    }

    public function guardarTipoIngresoSalida(Request $request){

        if($request->ajax()){

            // dd($request->all());

            $usuario         = Auth::user();
            $monto           = $request->input('monto');
            $descripcion     = $request->input('descripcion');
            $tipo            = $request->input('tipo');
            $subcategoria_id = $request->input('subcategoria_id');
            $sucursal        = $usuario->sucursal;

            // $caja = new Caja();
            // $cajaVigente = $caja->sacaCajaVigente($sucursal->id);

            $pago                     = new pago();
            $pago->usuario_creador_id = $usuario->id;
            $pago->sucursal_id        = $sucursal->id;
              // $pago->caja_id            = $cajaVigente->id;
            $pago->monto            = $monto;
            $pago->fecha            = date('Y-m-d H:i:s');
            $pago->descripcion      = $descripcion;
            $pago->tipo_pago        = 'EFECTIVO';
            $pago->estado           = $tipo;
            // $pago->apertura_caja    = "No";
            $pago->sub_categoria_id = $subcategoria_id;
            $pago->save();

            $data = Respuesta::success(null, "Datos registrados correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }
}
