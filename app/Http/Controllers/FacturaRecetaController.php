<?php

namespace App\Http\Controllers;

use App\Models\Caracteristica;
use App\Models\Color_tela;
use App\Models\Factura;
use App\Models\FacturaReceta;
use App\Models\FacturaRecetaDetalle;
use App\Models\Focalizado;
use App\Models\Nevado;
use App\Models\Nombre_tela;
use App\Models\Order_trabajo;
use App\Models\Prelavado;
use App\Models\Producto;
use App\Models\Receta;
use App\Models\Tipo_proceso;
use App\Models\Tipo_tela;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FacturaRecetaController extends Controller
{

    public function datosModal(
        Request $request
    ) {

        if (!$request->ajax()) {

            return Respuesta::error(
                'Solicitud no válida.'
            );
        }


        $factura_id =
            $request->input(
                'factura_id'
            );


        $order_trabajo_id =
            $request->input(
                'order_trabajo_id'
            );


        /*
    |--------------------------------------------------------------------------
    | FACTURA
    |--------------------------------------------------------------------------
    */

        $factura =
            Factura::where(
                'id',
                $factura_id
            )
            ->whereNull(
                'deleted_at'
            )
            ->first();


        if (!$factura) {

            return Respuesta::error(
                'La factura no existe.'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | ORDEN DE TRABAJO
    |--------------------------------------------------------------------------
    */

        $orderTrabajo =
            Order_trabajo::where(
                'id',
                $order_trabajo_id
            )
            ->where(
                'factura_id',
                $factura_id
            )
            ->whereNull(
                'deleted_at'
            )
            ->first();


        if (!$orderTrabajo) {

            return Respuesta::error(
                'La orden de trabajo no existe.'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | RECETAS MAESTRAS
    |--------------------------------------------------------------------------
    */

        $recetas =
            Receta::whereNull(
                'deleted_at'
            )
            ->orderBy(
                'nombre'
            )
            ->get([
                'id',
                'nombre',
                'descripcion'
            ]);


        /*
    |--------------------------------------------------------------------------
    | CATÁLOGOS
    |--------------------------------------------------------------------------
    */

        $tipoTelas =
            Tipo_tela::whereNull(
                'deleted_at'
            )
            ->orderBy('nombre')
            ->get();


        $colorTelas =
            Color_tela::whereNull(
                'deleted_at'
            )
            ->orderBy('nombre')
            ->get();


        $nombreTelas =
            Nombre_tela::whereNull(
                'deleted_at'
            )
            ->orderBy('nombre')
            ->get();


        $prelavados =
            Prelavado::whereNull(
                'deleted_at'
            )
            ->orderBy('nombre')
            ->get();


        $focalizados =
            Focalizado::whereNull(
                'deleted_at'
            )
            ->orderBy('nombre')
            ->get();


        $nevados =
            Nevado::whereNull(
                'deleted_at'
            )
            ->orderBy('nombre')
            ->get();


        $caracteristicas =
            Caracteristica::whereNull(
                'deleted_at'
            )
            ->orderBy('nombre')
            ->get();


        $tipoProcesos =
            Tipo_proceso::whereNull(
                'deleted_at'
            )
            ->orderBy('nombre')
            ->get();


        $productos =
            Producto::with([
                'ultimoIngreso'
            ])
            ->whereNull(
                'deleted_at'
            )
            ->orderBy(
                'nombre'
            )
            ->get();


        /*
    |--------------------------------------------------------------------------
    | RECETA YA ASOCIADA A ESTA OT
    |--------------------------------------------------------------------------
    */

        $facturaReceta =
            FacturaReceta::with([
                'receta',

                'detalles' => function (
                    $query
                ) {

                    $query
                        ->whereNull(
                            'deleted_at'
                        )
                        ->orderBy(
                            'orden_proceso'
                        )
                        ->orderBy(
                            'orden_producto'
                        );
                },

                'detalles.tipoProceso',

                'detalles.producto'
            ])
            ->where(
                'factura_id',
                $factura_id
            )
            ->where(
                'order_trabajo_id',
                $order_trabajo_id
            )
            ->whereNull(
                'deleted_at'
            )
            ->first();


        return Respuesta::success(
            [

                'orderTrabajo'
                => $orderTrabajo,

                'recetas'
                => $recetas,

                'facturaReceta'
                => $facturaReceta,

                'tipoTelas'
                => $tipoTelas,

                'colorTelas'
                => $colorTelas,

                'nombreTelas'
                => $nombreTelas,

                'prelavados'
                => $prelavados,

                'focalizados'
                => $focalizados,

                'nevados'
                => $nevados,

                'caracteristicas'
                => $caracteristicas,

                'tipoProcesos'
                => $tipoProcesos,

                'productos'
                => $productos
            ],
            'Datos cargados correctamente.'
        );
    }

    public function obtenerReceta(Request $request)
    {
        if (!$request->ajax()) {

            return Respuesta::error(
                null,
                'Solicitud incorrecta'
            );
        }


        $receta_id =
            $request->input('receta_id');


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
            ->where(
                'id',
                $receta_id
            )
            ->whereNull(
                'deleted_at'
            )
            ->first();


        if (!$receta) {
            return Respuesta::error(
                null,
                'No se encontró la receta.'
            );
        }

        foreach ($receta->detalles as $detalle) {
            $detalle->receta_detalle_id = $detalle->id;
        }

        return Respuesta::success(
            [
                'receta' => $receta
            ],
            'Receta obtenida correctamente'
        );
    }


    public function guardar(Request $request)
    {
        if (!$request->ajax()) {

            return Respuesta::error(
                null,
                'Solicitud incorrecta'
            );
        }


        $usuario           = Auth::user();
        $factura_id        = $request->input('factura_id');
        $order_trabajo_id  = $request->input('order_trabajo_id');
        $factura_receta_id = $request->input('factura_receta_id');
        $receta_id         = $request->input('receta_id');


        DB::beginTransaction();


        try {

            /*
        |--------------------------------------------------------------------------
        | VALIDAMOS FACTURA
        |--------------------------------------------------------------------------
        */

            $factura =
                Factura::where(
                    'id',
                    $factura_id
                )
                ->whereNull(
                    'deleted_at'
                )
                ->first();


            if (!$factura) {

                DB::rollBack();

                return Respuesta::error(
                    null,
                    'La factura no existe.'
                );
            }


            /*
        |--------------------------------------------------------------------------
        | VALIDAMOS ORDEN DE TRABAJO
        |--------------------------------------------------------------------------
        |
        | La receta pertenece a una Orden de Trabajo específica.
        | Además validamos que esa OT realmente pertenezca a la factura.
        |
        */

            $orderTrabajo =
                Order_trabajo::where(
                    'id',
                    $order_trabajo_id
                )
                ->where(
                    'factura_id',
                    $factura_id
                )
                ->whereNull(
                    'deleted_at'
                )
                ->first();


            if (!$orderTrabajo) {

                DB::rollBack();

                return Respuesta::error(
                    null,
                    'La orden de trabajo no existe o no pertenece a la factura.'
                );
            }


            /*
        |--------------------------------------------------------------------------
        | NUEVO / EDITAR
        |--------------------------------------------------------------------------
        */

            if (
                empty($factura_receta_id)
                ||
                $factura_receta_id == 0
            ) {

                /*
            |--------------------------------------------------------------------------
            | BUSCAMOS SI ESTA OT YA TIENE UNA RECETA
            |--------------------------------------------------------------------------
            |
            | IMPORTANTE:
            | Ya no buscamos solamente por factura.
            |
            | Una factura puede tener:
            |
            | OT 1 -> Receta A
            | OT 2 -> Receta B
            | OT 3 -> Receta C
            |
            */

                $existe =
                    FacturaReceta::where(
                        'factura_id',
                        $factura_id
                    )
                    ->where(
                        'order_trabajo_id',
                        $order_trabajo_id
                    )
                    ->whereNull(
                        'deleted_at'
                    )
                    ->first();


                if ($existe) {

                    /*
                 * Ya existe una receta para esta OT.
                 * Entonces la editamos.
                 */

                    $facturaReceta =
                        $existe;


                    $facturaReceta
                        ->usuario_modificador_id =
                        $usuario->id;
                } else {

                    /*
                 * Nueva receta para esta OT.
                 */

                    $facturaReceta =
                        new FacturaReceta();


                    $facturaReceta
                        ->usuario_creador_id =
                        $usuario->id;


                    $facturaReceta
                        ->factura_id =
                        $factura_id;


                    $facturaReceta
                        ->order_trabajo_id =
                        $order_trabajo_id;
                }
            } else {

                /*
            |--------------------------------------------------------------------------
            | EDITAMOS UNA RECETA EXISTENTE
            |--------------------------------------------------------------------------
            |
            | Además de factura_id validamos order_trabajo_id.
            | Así nunca podremos editar accidentalmente la receta de otra OT.
            |
            */

                $facturaReceta =
                    FacturaReceta::where(
                        'id',
                        $factura_receta_id
                    )
                    ->where(
                        'factura_id',
                        $factura_id
                    )
                    ->where(
                        'order_trabajo_id',
                        $order_trabajo_id
                    )
                    ->whereNull(
                        'deleted_at'
                    )
                    ->first();


                if (!$facturaReceta) {

                    DB::rollBack();

                    return Respuesta::error(
                        null,
                        'No se encontró la receta asociada a esta orden de trabajo.'
                    );
                }


                $facturaReceta
                    ->usuario_modificador_id =
                    $usuario->id;
            }


            /*
        |--------------------------------------------------------------------------
        | ASEGURAMOS RELACIÓN CON FACTURA Y OT
        |--------------------------------------------------------------------------
        |
        | Aunque estemos editando, dejamos garantizado que los valores
        | correspondan a la factura y OT actuales.
        |
        */

            $facturaReceta->factura_id =
                $factura_id;


            $facturaReceta->order_trabajo_id =
                $order_trabajo_id;


            /*
        |--------------------------------------------------------------------------
        | RECETA MAESTRA DE ORIGEN
        |--------------------------------------------------------------------------
        */

            $facturaReceta->receta_id =
                $receta_id ?: null;


            /*
        |--------------------------------------------------------------------------
        | CABECERA EDITABLE
        |--------------------------------------------------------------------------
        */

            $facturaReceta->tipo_tela_id =
                $request->input(
                    'tipo_tela_id'
                );


            $facturaReceta->color_tela_id =
                $request->input(
                    'color_tela_id'
                );


            $facturaReceta->nombre_tela_id =
                $request->input(
                    'nombre_tela_id'
                );


            $facturaReceta->prelavado_id =
                $request->input(
                    'prelavado_id'
                );


            $facturaReceta->focalizado_id =
                $request->input(
                    'focalizado_id'
                );


            $facturaReceta->nevado_id =
                $request->input(
                    'nevado_id'
                );


            $facturaReceta->caracteristica_id =
                $request->input(
                    'caracteristica_id'
                );


            $facturaReceta->tipo_proceso_id =
                $request->input(
                    'tipo_proceso_id'
                );


            $facturaReceta->nombre =
                $request->input(
                    'nombre'
                );


            $facturaReceta->descripcion =
                $request->input(
                    'descripcion'
                );


            $facturaReceta->save();


            /*
        |--------------------------------------------------------------------------
        | ELIMINAMOS LÓGICAMENTE LOS DETALLES ANTERIORES
        |--------------------------------------------------------------------------
        |
        | Al guardar nuevamente una receta:
        |
        | - conservamos la cabecera factura_recetas
        | - eliminamos lógicamente sus detalles anteriores
        | - volvemos a insertar lo que actualmente está en pantalla
        |
        */

            FacturaRecetaDetalle::where(
                'factura_receta_id',
                $facturaReceta->id
            )
                ->whereNull(
                    'deleted_at'
                )
                ->update([
                    'usuario_eliminador_id'
                    => $usuario->id,

                    'deleted_at'
                    => now(),

                    'updated_at'
                    => now(),
                ]);


            /*
        |--------------------------------------------------------------------------
        | PROCESOS RECIBIDOS
        |--------------------------------------------------------------------------
        */

            $procesos =
                $request->input(
                    'procesos',
                    []
                );


            foreach (
                $procesos
                as
                $proceso
            ) {

                /*
             * Si no seleccionó proceso,
             * ignoramos este bloque.
             */

                if (
                    empty($proceso['tipo_proceso_id'])
                ) {

                    continue;
                }


                $productos =
                    $proceso['productos']
                    ?? [];


                foreach (
                    $productos
                    as
                    $producto
                ) {

                    /*
                 * Si no seleccionó producto,
                 * no insertamos detalle.
                 */

                    if (
                        empty($producto['producto_id'])
                    ) {

                        continue;
                    }


                    $detalle =
                        new FacturaRecetaDetalle();


                    /*
                |--------------------------------------------------------------------------
                | AUDITORÍA
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->usuario_creador_id =
                        $usuario->id;


                    /*
                |--------------------------------------------------------------------------
                | FACTURA RECETA
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->factura_receta_id =
                        $facturaReceta->id;


                    /*
                |--------------------------------------------------------------------------
                | DETALLE DE RECETA MAESTRA DE ORIGEN
                |--------------------------------------------------------------------------
                |
                | Si el producto vino originalmente de receta_detalles,
                | conservamos su id.
                |
                */

                    $detalle
                        ->receta_detalle_id =
                        $producto['receta_detalle_id']
                        ?? null;


                    /*
                |--------------------------------------------------------------------------
                | PROCESO
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->tipo_proceso_id =
                        $proceso['tipo_proceso_id'];


                    /*
                |--------------------------------------------------------------------------
                | PRODUCTO
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->producto_id =
                        $producto['producto_id'];


                    /*
                |--------------------------------------------------------------------------
                | ORDEN DEL PROCESO
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->orden_proceso =
                        $proceso['orden_proceso']
                        ?? null;


                    /*
                |--------------------------------------------------------------------------
                | ORDEN DEL PRODUCTO
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->orden_producto =
                        $producto['orden_producto']
                        ?? null;


                    /*
                |--------------------------------------------------------------------------
                | PORCENTAJE
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->porcentaje =
                        $producto['porcentaje']
                        ?? null;


                    /*
                |--------------------------------------------------------------------------
                | CANTIDAD
                |--------------------------------------------------------------------------
                |
                | Esta cantidad ya debe venir calculada en frontend
                | según:
                |
                | peso de la OT × porcentaje
                |
                */

                    $detalle
                        ->cantidad =
                        $producto['cantidad']
                        ?? null;


                    /*
                |--------------------------------------------------------------------------
                | TOTAL
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->total =
                        $producto['total']
                        ?? null;


                    /*
                |--------------------------------------------------------------------------
                | TIEMPO
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->tiempo =
                        $producto['tiempo']
                        ?? null;


                    /*
                |--------------------------------------------------------------------------
                | TEMPERATURA
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->temperatura =
                        $producto['temperatura']
                        ?? null;


                    /*
                |--------------------------------------------------------------------------
                | PH
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->ph =
                        $producto['ph']
                        ?? null;


                    /*
                |--------------------------------------------------------------------------
                | RB
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->rb =
                        $producto['rb']
                        ?? null;


                    /*
                |--------------------------------------------------------------------------
                | DESCRIPCIÓN
                |--------------------------------------------------------------------------
                */

                    $detalle
                        ->descripcion =
                        $producto['descripcion']
                        ?? null;


                    $detalle->save();
                }
            }


            /*
        |--------------------------------------------------------------------------
        | FINALIZAMOS
        |--------------------------------------------------------------------------
        */

            DB::commit();


            return Respuesta::success(
                [
                    'factura_receta_id'
                    => $facturaReceta->id,

                    'order_trabajo_id'
                    => $orderTrabajo->id,

                    'nro_ot'
                    => $orderTrabajo->nro_ot,

                    'peso'
                    => $orderTrabajo->peso
                ],
                'La receta de la orden de trabajo fue guardada correctamente.'
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
                'Solicitud incorrecta'
            );
        }


        $usuario =
            Auth::user();


        $factura_receta_id =
            $request->input(
                'factura_receta_id'
            );


        $factura_id =
            $request->input(
                'factura_id'
            );


        DB::beginTransaction();


        try {


            $facturaReceta =
                FacturaReceta::where(
                    'id',
                    $factura_receta_id
                )
                ->where(
                    'factura_id',
                    $factura_id
                )
                ->whereNull(
                    'deleted_at'
                )
                ->first();


            if (!$facturaReceta) {

                DB::rollBack();

                return Respuesta::error(
                    null,
                    'No existe la receta asociada.'
                );
            }



            /*
        |--------------------------------------------------------------------------
        | DETALLES
        |--------------------------------------------------------------------------
        */

            FacturaRecetaDetalle::where(
                'factura_receta_id',
                $facturaReceta->id
            )
                ->whereNull(
                    'deleted_at'
                )
                ->update([

                    'usuario_eliminador_id'
                    => $usuario->id,

                    'deleted_at'
                    => now(),

                    'updated_at'
                    => now(),
                ]);



            /*
        |--------------------------------------------------------------------------
        | CABECERA
        |--------------------------------------------------------------------------
        */

            $facturaReceta
                ->usuario_eliminador_id =
                $usuario->id;

            $facturaReceta
                ->deleted_at =
                now();

            $facturaReceta->save();


            DB::commit();


            return Respuesta::success(
                null,
                'La receta fue eliminada correctamente.'
            );
        } catch (\Throwable $e) {


            DB::rollBack();


            return Respuesta::error(
                null,
                $e->getMessage()
            );
        }
    }
}
