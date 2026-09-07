<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Models\Color_tela;
use App\Models\Focalizado;
use App\Models\Nevado;
use App\Models\Nombre_tela;
use App\Models\Prelavado;
use App\Models\Producto;
use App\Models\Receta;
use App\Models\RecetaDetalle;
use App\Models\Tipo_proceso;
use App\Models\Tipo_tela;
use App\Utils\Respuesta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecetaController extends Controller
{
    public function listado()
    {
        $tipoTelas = Tipo_tela::all();
        $colorTelas = Color_tela::all();
        $nombreTelas = Nombre_tela::all();
        $prelavados = Prelavado::all();
        $focalizados = Focalizado::all();
        $nevados = Nevado::all();
        $caracteristicas = Caracteristica::all();
        $tipoProcesos = Tipo_proceso::all();
        $productos = Producto::all();

        return view('receta.listado')->with(compact('tipoTelas', 'colorTelas', 'nombreTelas', 'prelavados', 'focalizados', 'nevados', 'caracteristicas', 'tipoProcesos', 'productos'));
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            // $recetas = Receta::all();

            $recetas = Receta::with([
                'tipoTela',
                'colorTela',
                'nombreTela',
                'tipoProceso',
                'prelavado',
                'focalizado',
                'caracteristica',
                'nevado',

                'detalles' => function ($query) {
                    $query->orderBy('orden_proceso', 'asc')
                        ->orderBy('orden_producto', 'asc');
                },

                'detalles.tipoProceso',
                'detalles.producto',
            ])
                ->whereNull('deleted_at')
                ->orderBy('id', 'desc')
                ->get();

            $listado = view(
                'receta.ajaxListado',
                compact('recetas')
            )->render();

            $valores = [
                'listado' => $listado
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardar(Request $request)
    {
        if (!$request->ajax()) {
            return Respuesta::error(
                null,
                "Error al obtener los datos"
            );
        }

        $usuario = Auth::user();

        $receta_id         = $request->input('receta_id');
        $nombre            = $request->input('nombre');
        $tipo_tela_id      = $request->input('tipo_tela_id');
        $color_tela_id     = $request->input('color_tela_id');
        $nombre_tela_id    = $request->input('nombre_tela_id');
        $prelavado_id      = $request->input('prelavado_id');
        $focalizado_id     = $request->input('focalizado_id');
        $nevado_id         = $request->input('nevado_id');
        $caracteristica_id = $request->input('caracteristica_id');
        $tipo_proceso_id   = $request->input('tipo_proceso_id');
        $descripcion       = $request->input('descripcion');

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | NUEVA RECETA
            |--------------------------------------------------------------------------
            */
                if (empty($receta_id) ||$receta_id == 0) {
                    $receta = new Receta();
                    $receta->usuario_creador_id = $usuario->id;

                    /*
                    |--------------------------------------------------------------------------
                    | EDITAR RECETA
                    |--------------------------------------------------------------------------
                    */
                } else {

                    $receta = Receta::where('id', $receta_id)
                        ->whereNull('deleted_at')
                        ->first();

                    if (!$receta) {
                        DB::rollBack();
                        return Respuesta::error(null,"La receta no existe.");
                    }
                    $receta->usuario_modificador_id = $usuario->id;
                }


            /*
            |--------------------------------------------------------------------------
            | DATOS CABECERA
            |--------------------------------------------------------------------------
            */

            $receta->tipo_tela_id       = $tipo_tela_id;
            $receta->color_tela_id      = $color_tela_id;
            $receta->nombre_tela_id     = $nombre_tela_id;
            $receta->tipo_proceso_id    = $tipo_proceso_id;
            $receta->prelavado_id       = $prelavado_id;
            $receta->focalizado_id      = $focalizado_id;
            $receta->caracteristica_id  = $caracteristica_id;
            $receta->nevado_id          = $nevado_id;
            $receta->nombre             = $nombre;
            $receta->descripcion        = $descripcion;

            $receta->save();

            /*
            |--------------------------------------------------------------------------
            | SI ESTAMOS EDITANDO
            | ELIMINAMOS LÓGICAMENTE LOS DETALLES ANTERIORES
            |--------------------------------------------------------------------------
            */

            if (!empty($receta_id) &&$receta_id != 0) {
                RecetaDetalle::where('receta_id',$receta->id)
                    ->whereNull('deleted_at')
                    ->update([
                        'usuario_eliminador_id' => $usuario->id,
                        'deleted_at' => now(),
                        'updated_at' => now(),
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | GUARDAMOS NUEVAMENTE LOS PROCESOS Y PRODUCTOS
            |--------------------------------------------------------------------------
            */

            $procesos = $request->input(
                'procesos',
                []
            );

            foreach ($procesos as $proceso) {

                /*
             * Por seguridad, si no hay proceso,
             * no guardamos esa fila.
             */
                if (empty($proceso['proceso_id'])) {
                    continue;
                }

                $productos = $proceso['productos']?? [];

                foreach ($productos as $producto) {

                    /*
                 * Si no seleccionó producto,
                 * no guardamos la fila.
                 */
                    if (empty($producto['producto_id'])) {
                        continue;
                    }

                    $recetaDetalle                     = new RecetaDetalle();
                    $recetaDetalle->usuario_creador_id = $usuario->id;
                    $recetaDetalle->receta_id          = $receta->id;
                    $recetaDetalle->tipo_proceso_id    = $proceso['proceso_id'];
                    $recetaDetalle->producto_id        = $producto['producto_id'];
                    $recetaDetalle->orden_proceso      = $proceso['orden_proceso']?? null;
                    $recetaDetalle->orden_producto     = $producto['orden_producto']?? null;
                    $recetaDetalle->porcentaje         = $producto['porcentaje']?? null;
                    $recetaDetalle->cantidad           = $producto['cantidad']?? null;
                    $recetaDetalle->total              = $producto['total']?? null;
                    $recetaDetalle->tiempo             = $producto['tiempo']?? null;
                    $recetaDetalle->temperatura        = $producto['temperatura']?? null;
                    $recetaDetalle->ph                 = $producto['ph']?? null;
                    $recetaDetalle->rb                 = $producto['rb']?? null;
                    $recetaDetalle->descripcion        = $producto['descripcion']?? null;
                    $recetaDetalle->save();
                }
            }


            DB::commit();


            /*
        |--------------------------------------------------------------------------
        | RESPUESTA
        |--------------------------------------------------------------------------
        */

            if (
                empty($receta_id) ||
                $receta_id == 0
            ) {

                return Respuesta::success(
                    null,
                    "La receta fue registrada correctamente."
                );
            }

            return Respuesta::success(
                null,
                "La receta fue actualizada correctamente."
            );
        } catch (\Throwable $e) {

            DB::rollBack();

            return Respuesta::error(
                null,
                $e->getMessage()
            );
        }
    }

    public function eliminar(Request $request)
    {
        if (!$request->ajax()) {

            return Respuesta::error(
                null,
                "Error al realizar la operación"
            );
        }

        $usuario = Auth::user();

        $receta_id = $request->input('receta');

        DB::beginTransaction();

        try {

            /*
            |--------------------------------------------------------------------------
            | BUSCAMOS RECETA
            |--------------------------------------------------------------------------
            */

                $receta = Receta::where('id',$receta_id)
                    ->whereNull('deleted_at')
                    ->first();

                if (!$receta) {
                    DB::rollBack();
                    return Respuesta::error(
                        null,
                        "La receta no existe o ya fue eliminada."
                    );
                }


            /*
            |--------------------------------------------------------------------------
            | ELIMINAMOS LÓGICAMENTE LOS DETALLES
            |--------------------------------------------------------------------------
            */

                RecetaDetalle::where('receta_id',$receta->id)
                    ->whereNull('deleted_at')
                    ->update([
                        'usuario_eliminador_id' => $usuario->id,
                        'deleted_at' => now(),
                        'updated_at' => now(),
                    ]);

            /*
            |--------------------------------------------------------------------------
            | ELIMINAMOS LÓGICAMENTE LA RECETA
            |--------------------------------------------------------------------------
            */

            $receta->usuario_eliminador_id = $usuario->id;
            $receta->deleted_at = now();
            $receta->save();

            DB::commit();

            return Respuesta::success(
                null,
                "La receta fue eliminada correctamente."
            );
        } catch (\Throwable $e) {

            DB::rollBack();

            return Respuesta::error(
                null,
                $e->getMessage()
            );
        }
    }

    public function pdf($receta_id)
    {
        $receta = Receta::with([

            'tipoTela',

            'colorTela',

            'nombreTela',

            'tipoProceso',

            'prelavado',

            'focalizado',

            'caracteristica',

            'nevado',

            'detalles' => function ($query) {

                $query
                    ->whereNull('deleted_at')
                    ->orderBy(
                        'orden_proceso',
                        'asc'
                    )
                    ->orderBy(
                        'orden_producto',
                        'asc'
                    );
            },

            'detalles.tipoProceso',

            'detalles.producto',

        ])
            ->where('id', $receta_id)
            ->whereNull('deleted_at')
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | AGRUPAMOS LOS PRODUCTOS POR PROCESO
        |--------------------------------------------------------------------------
        */

        $procesos = [];

        foreach ($receta->detalles as $detalle) {

            /*
         * Usamos orden_proceso + tipo_proceso_id.
         *
         * Esto es importante porque puede existir
         * el mismo tipo de proceso más de una vez.
         */

            $clave =
                $detalle->orden_proceso
                . '_'
                . $detalle->tipo_proceso_id;


            if (!isset($procesos[$clave])) {

                $procesos[$clave] = [

                    'orden_proceso'
                    => $detalle->orden_proceso,

                    'tipo_proceso_id'
                    => $detalle->tipo_proceso_id,

                    'nombre_proceso'
                    => $detalle->tipoProceso?->nombre
                        ?? 'PROCESO',

                    'productos'
                    => [],

                ];
            }


            $procesos[$clave]['productos'][] =
                $detalle;
        }


        /*
        |--------------------------------------------------------------------------
        | ORDENAMOS LOS PROCESOS
        |--------------------------------------------------------------------------
        */

        $procesos =
            collect($procesos)
            ->sortBy('orden_proceso')
            ->values();


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::loadView(
            'receta.pdf.pdf',
            compact(
                'receta',
                'procesos'
            )
        );


        $pdf->setPaper(
            'A4',
            'portrait'
        );


        return $pdf->stream(
            'RECETA_'
                . $receta->id
                . '.pdf'
        );
    }
}
