<?php
namespace App\Http\Controllers;

use App\Models\Deuda;
use App\Models\DeudaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DeudaController extends Controller
{
    public function listarPorUsuario($user_id)
    {
        $deudas = Deuda::where('user_id', $user_id)
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($deudas);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $deuda = Deuda::create([

                'usuario_creador_id' => auth()->id(),
                'user_id' => $request->user_id,
                'concepto' => $request->concepto,
                'descripcion' => $request->descripcion,
                'monto_total' => $request->monto_total,
                'monto_pagado' => 0,
                'saldo_pendiente' => $request->monto_total,

                'estado' => 'PENDIENTE',
                'fecha' => $request->fecha ?? now(),
            ]);

            DeudaDetalle::create([

                'usuario_creador_id' => auth()->id(),

                'deuda_id' => $deuda->id,
                'user_id' => $request->user_id,

                'tipo_movimiento' => 'INGRESO',

                'monto' => $request->monto_total,

                'descripcion' => 'Registro inicial de deuda',

                'fecha' => $request->fecha ?? now(),
                'estado' => 'ACTIVO',
            ]);

            DB::commit();

            return response()->json([
                'ok' => true,
                'deuda' => $deuda
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function descontar(Request $request)
    {
        DB::beginTransaction();

        try {
            $deuda = Deuda::findOrFail($request->deuda_id);
            $monto = floatval($request->monto);
            if ($monto > $deuda->saldo_pendiente) {

                return response()->json([
                    'ok' => false,
                    'error' => 'El monto supera el saldo pendiente'
                ], 400);
            }
            $deuda->monto_pagado += $monto;
            $deuda->saldo_pendiente -= $monto;
            if ($deuda->saldo_pendiente <= 0) {

                $deuda->saldo_pendiente = 0;
                $deuda->estado = 'PAGADO';
            }

            $deuda->save();
            DeudaDetalle::create([
                'usuario_creador_id' => auth()->id(),
                'deuda_id' => $deuda->id,
                'user_id' => $deuda->user_id,
                'pago_id' => $request->pago_id,
                'tipo_movimiento' => 'SALIDA',
                'monto' => $monto,
                'descripcion' => $request->descripcion ?? 'Descuento aplicado al salario',
                'fecha' => now(),
                'estado' => 'ACTIVO',
            ]);

            DB::commit();

            return response()->json([
                'ok' => true,
                'deuda' => $deuda
            ]);
        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'ok' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function movimientos($deuda_id)
    {
        $movimientos = DeudaDetalle::where('deuda_id', $deuda_id)
            ->whereNull('deleted_at')
            ->orderBy('id', 'desc')
            ->get();

        return response()->json($movimientos);
    }
}