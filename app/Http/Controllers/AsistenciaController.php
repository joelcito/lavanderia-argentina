<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
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
        $asistencia = Asistencia::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'user_id' => $request->user_id,
                'fecha' => $request->fecha,
                'hora_entrada' => $request->hora_entrada,
                'hora_salida' => $request->hora_salida
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function delete(Request $request)
    {
        Asistencia::where('id', $request->id)->delete();

        return response()->json(['ok' => true]);
    }

    public function listar($user_id)
    {
        return Asistencia::where('user_id', $user_id)
            ->orderBy('fecha', 'desc')
            ->get();
    }

}
