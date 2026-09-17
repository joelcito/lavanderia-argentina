<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Pago;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
class PagosPersonalController extends Controller
{
    public function ajaxResumen($userId)
    {
        $user = User::findOrFail($userId);

        $asistencias = Asistencia::where('user_id', $userId)->get();

        $totalHoras = 0;

        foreach ($asistencias as $a) {
            if ($a->hora_entrada && $a->hora_salida) {
                $entrada = Carbon::parse($a->hora_entrada);
                $salida = Carbon::parse($a->hora_salida);
                $totalHoras += $salida->diffInHours($entrada);
            }
        }

        $dias = ceil($totalHoras / $user->horas_base);
        $totalPago = $dias * $user->pago_diario;

        return response()->json([
            'horas' => $totalHoras,
            'dias' => $dias,
            'pago' => $totalPago
        ]);
    }

    public function store(Request $request)
    {
        Pago::create([
            'user_id' => $request->user_id,
            'monto' => $request->monto,
            'fecha' => now()
        ]);

        return response()->json(['success' => true]);
    }

    public function historial($userId)
    {
        $pagos = Pago::where('user_id', $userId)
            ->orderBy('fecha', 'desc')
            ->get();

        return response()->json($pagos);
    }
}
