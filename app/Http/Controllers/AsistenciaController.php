<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function ajaxListado($user_id)
    {
        $asistencias = Asistencia::where('user_id', $user_id)->get();

        return response()->json($asistencias);
    }

    public function store(Request $request)
    {
        $entrada = Carbon::parse($request->fecha . ' ' . $request->hora_entrada);

        $salida = Carbon::parse($request->fecha . ' ' . $request->hora_salida);

        if ($salida->lessThanOrEqualTo($entrada)) {
            $salida->addDay();
        }

        $existe = Asistencia::where('user_id', $request->user_id)
            ->where('fecha', $request->fecha)
            ->where('hora_entrada', $entrada->format('H:i:s'))
            ->where('hora_salida', $salida->format('H:i:s'))
            ->when($request->id, function ($q) use ($request) {
                $q->where('id', '!=', $request->id);
            })
            ->exists();

        if ($existe) {
            return response()->json([
                'ok' => false,
                'message' => 'Ya existe una asistencia con el mismo horario'
            ], 422);
        }
        $asistencia = Asistencia::updateOrCreate(
            ['id' => $request->id],
            [
                'user_id' => $request->user_id,
                'fecha' => $request->fecha,
                'hora_entrada' => $entrada->format('H:i:s'),
                'hora_salida' => $salida->format('H:i:s'),
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function delete(Request $request)
    {
        $asistencia = Asistencia::find($request->id);

        if (!$asistencia) {
            return response()->json([
                'ok' => false,
                'message' => 'Registro no encontrado'
            ], 404);
        }

        $asistencia->usuario_eliminador_id = auth()->id();
        $asistencia->save();


        $asistencia->delete();

        return response()->json(['ok' => true]);
    }

    public function listar($user_id)
    {
        return Asistencia::where('user_id', $user_id)
            ->orderBy('fecha', 'desc')
            ->get();
    }

}
