<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ConfiguracionPersonalController extends Controller
{
    public function update(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        $user->pago_diario = $request->pago_diario;
        $user->horas_base = $request->horas_base;

        $user->save();

        return response()->json([
            'estado' => true
        ]);
    }
}
