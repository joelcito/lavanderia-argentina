<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\SolicitudDetalleProceso;
use App\Models\User;
use App\Models\Pago;
use App\Models\Sucursal;
use App\Models\Order_trabajo;

use Carbon\Carbon;
use App\Models\Asistencia;
use DB;
use Illuminate\Http\Request;

class ControlPersonalController extends Controller
{
    public function index()
    {
        return view('personal.index');
    }

    public function ajaxListado()
    {
        $usuarios = User::whereIn('rol_id', [2, 8, 5, 6])->get();
        return view('personal.ajaxListado', compact('usuarios'));
    }

    public function show(User $user)
    {
        return view('personal.show', compact('user'));
    }

    public function getUser($id)
    {
        return response()->json(User::findOrFail($id));
    }

    public function formularioLavador()
    {
        $sucursales = Sucursal::all();

        return view('personal.personalLavador', compact('sucursales'));
    }

    public function formularioAuxiliar()
    {
        $sucursales = Sucursal::all();

        return view('personal.personalAuxiliar', compact('sucursales'));
    }

    public function formularioFocalizador()
    {
        $sucursales = Sucursal::all();
        $usuarios = User::where('rol_id', 6)->get(); // 🔥 AGREGA ESTO

        return view('personal.personalFocalizador', compact('sucursales', 'usuarios'));
    }

    public function formularioPlanchador()
    {
        $usuarios = User::where('rol_id', 5)->get(); // planchadores   
        $sucursales = Sucursal::all();

        return view('personal.personalPlanchador', compact('usuarios', 'sucursales'));
    }


    public function resumenFechas(Request $request, $user_id)
    {
        $inicio = $request->inicio;
        $fin = $request->fin;
        $usuario = User::find($user_id);

        $asistencias = Asistencia::where('user_id', $user_id)
            ->whereBetween('fecha', [$inicio, $fin])
            ->whereNotExists(function ($q) {
                $q->select(DB::raw(1))
                    ->from('pagos')
                    ->whereColumn('pagos.user_id', 'asistencias.user_id')
                    ->where('pagos.tipo_pago', 'salario')
                    ->whereRaw("asistencias.fecha BETWEEN pagos.fecha_inicio AND pagos.fecha_fin");
            })
            ->orderBy('fecha')
            ->get()
            ->groupBy('fecha');


        $totalSegundos = 0;
        $dias = 0;
        $detalle = [];
        foreach ($asistencias as $fecha => $registros) {

            $segundosDia = 0;
            foreach ($registros as $a) {
                if (!$a->hora_entrada || !$a->hora_salida)
                    continue;
                $entrada = strtotime($a->hora_entrada);
                $salida = strtotime($a->hora_salida);
                if ($salida <= $entrada)
                    continue;
                $segundosDia += ($salida - $entrada);
            }
            if ($segundosDia > 0) {
                $dias++;
            }
            $totalSegundos += $segundosDia;
            $horas = floor($segundosDia / 3600);
            $minutos = floor(($segundosDia % 3600) / 60);
            $detalle[] = [
                'fecha' => $fecha,
                'dia' =>
                    Carbon::parse($fecha)->locale('es')->dayName,
                'horas_texto' => sprintf('%02d:%02d', $horas, $minutos),
                'segundos' => $segundosDia
            ];
        }
        $pagoHora = 0;
        $pagoMinuto = 0;
        $pagoTotal = 0;
        if ($usuario->horas_base > 0) {
            $pagoHora = $usuario->pago_diario / $usuario->horas_base;
            $pagoMinuto = $pagoHora / 60;
            $totalMinutos = $totalSegundos / 60;
            $pagoTotal = $totalMinutos * $pagoMinuto;
        }

        $rangoFechas = function ($q) use ($inicio, $fin) {
            $q->whereBetween('fecha_inicio', [$inicio, $fin])
                ->orWhereBetween('fecha_fin', [$inicio, $fin])
                ->orWhere(function ($q2) use ($inicio, $fin) {
                    $q2->where('fecha_inicio', '<=', $inicio)
                        ->where('fecha_fin', '>=', $fin);
                });
        };



        $adelantos = Pago::where('user_id', $user_id)
            ->where('tipo_pago', 'adelanto')
            ->where($rangoFechas)
            ->sum('monto');
        $descuentos = Pago::where('user_id', $user_id)
            ->where('tipo_pago', 'descuento')
            ->where($rangoFechas)
            ->sum('monto');
        $ajustes = Pago::where('user_id', $user_id)
            ->whereIn('tipo_pago', ['adelanto', 'descuento'])
            ->where($rangoFechas)
            ->orderBy('fecha', 'desc')
            ->get(['tipo_pago', 'monto', 'descripcion', 'fecha']);
        $pagoRealizado = Pago::where('user_id', $user_id)
            ->where('tipo_pago', 'salario')
            ->where('fecha_inicio', $inicio)
            ->where('fecha_fin', $fin)->first();
        // $pagos = Pago::where('user_id', $user_id)
        //     ->where('tipo_pago', 'salario')
        //     ->where($rangoFechas)
        //     ->sum('monto');
        //$totalFinal = $pagoTotal - $adelantos - $descuentos - $pagos;
        $totalFinal = $pagoTotal - $adelantos - $descuentos;
        return response()->json([
            'total_horas'
            => round($totalSegundos / 3600, 2),
            'total_minutos' => round($totalSegundos / 60, 2),
            'dias' => $dias,
            'pago_hora' => round($pagoHora, 2),
            'pago_minuto' => round($pagoMinuto, 4),
            'pago_total' => round($pagoTotal, 2),
            'adelantos' => round($adelantos, 2),
            'descuentos' => round($descuentos, 2),
            'total_final' => round($totalFinal, 2),
            'pago_diario' => $usuario->pago_diario,
            'horas_base' =>
                $usuario->horas_base,
            'ajustes' => $ajustes,
            'inicio' => $inicio,
            'fin'
            => $fin,
            'pago_realizado' => $pagoRealizado ? true : false,
            'pago_info' => $pagoRealizado,
            //'pagos' => round($pagos, 2),
            'detalle' => $detalle
        ]);


    }


    public function store(Request $request)
    {
        $usuario = User::find($request->user_id);
        $yaPagado = Pago::where('user_id', $request->user_id)
            ->where('tipo_pago', 'salario')
            ->where('fecha_inicio', $request->fecha_inicio)
            ->where('fecha_fin', $request->fecha_fin)
            ->exists();

        if ($yaPagado && $request->tipo_pago !== 'salario') {
            return response()->json([
                'error' => 'Este periodo ya fue pagado'
            ], 400);
        }

        if ($request->tipo_pago === 'salario' && $yaPagado) {
            return response()->json([
                'error' => 'Ya se pagó este periodo'
            ], 400);
        }
        $montoFinal = round($request->monto, 2);
        $montoCalculado = round($request->monto_calculado ?? 0, 2);

        Pago::create([
            'user_id' => $request->user_id,
            'usuario_creador_id' => auth()->id(),

            'monto' => $montoFinal,
            'tipo_pago' => $request->tipo_pago,
            'descripcion' => $request->descripcion,
            'fecha' => $request->fecha ?? now(),

            'fecha_inicio' => $request->fecha_inicio ?? null,
            'fecha_fin' => $request->fecha_fin ?? null,

            'pago_diario_usado' => $usuario->pago_diario ?? 0,
            'horas_base_usado' => $usuario->horas_base ?? 0,

            'total_horas' => $request->total_horas ?? 0,
            'total_minutos' => $request->total_minutos ?? 0,
            'monto_calculado' => $montoCalculado ?? 0,
            'total_descuentos' => $request->total_descuentos ?? 0,

            'estado' => 'SALIDA'
        ]);

        return response()->json(['ok' => true]);
    }



}
