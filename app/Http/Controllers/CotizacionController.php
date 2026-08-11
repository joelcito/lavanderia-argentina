<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Color_tela;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\Focalizado;
use App\Models\Nevado;
use App\Models\Prelavado;
use App\Models\Prenda;
use App\Models\Producto;
use App\Models\Tipo_proceso;
use App\Models\Tipo_tela;
use App\Models\User;
use App\Utils\Respuesta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function listado()
    {
        $tipoProcesos = Tipo_proceso::all();
        $productos = Producto::with('ultimoIngreso')->orderBy('nombre')->get();
        $prelavados = Prelavado::orderBy('nombre')->get();
        $nevados = Nevado::orderBy('nombre')->get();
        $focalizados = Focalizado::orderBy('nombre')->get();

        $tipoTelas = Tipo_tela::orderBy('nombre')->get();
        $colorTelas = Color_tela::orderBy('nombre')->get();
        $tipoPrendas = Prenda::orderBy('nombre')->get();

        $clientes = User::where('rol_id', 3)->orderBy('id', 'desc')->get();

        return view('cotizacion.listado')->with(compact('tipoProcesos','productos', 'prelavados', 'nevados','focalizados', 'tipoTelas', 'colorTelas','tipoPrendas','clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function ajaxListado(Request $request)
    {
        if($request->ajax()){

            $cliente_id     = $request->input('buscar_cliente_id');
            $fecha_ini      = $request->input('buscar_fecha_ini');
            $fecha_fin      = $request->input('buscar_fecha_fin');
            $tipo_prenda_id = $request->input('buscar_tipo_prenda_id');
            $color_tela_id  = $request->input('buscar_color_tela_id');
            $prelavado_id   = $request->input('buscar_prelavado_id');
            $tipo_tela_id   = $request->input('buscar_tipo_tela_id');
            $nevado_id      = $request->input('buscar_nevado_id');
            $focalizado_id  = $request->input('buscar_focalizado_id');

            $query = Cotizacion::query();

            if(!is_null($cliente_id)){
                $query->where('cliente_id', $cliente_id);
            }

            if (!is_null($fecha_ini) && !is_null($fecha_fin)) {
                $query->whereBetween('created_at', [$fecha_ini." 00:00:00", $fecha_fin . " 23:59:59"]);
            }

            if (!is_null($tipo_prenda_id)) {
                $query->where('prenda_id', $tipo_prenda_id);
            }

            if (!is_null($color_tela_id)) {
                $query->where('color_tela_id', $color_tela_id);
            }

            if (!is_null($prelavado_id)) {
                $query->where('prelavado_id', $prelavado_id);
            }

            if (!is_null($tipo_tela_id)) {
                $query->where('tipo_tela_id', $tipo_tela_id);
            }

            if (!is_null($nevado_id)) {
                $query->where('nevado_id', $nevado_id);
            }

            if (!is_null($focalizado_id)) {
                $query->where('focalizado_id', $focalizado_id);
            }

            if (
                !is_null($cliente_id) &&
                !is_null($fecha_ini) &&
                !is_null($fecha_fin) &&
                !is_null($tipo_prenda_id) &&
                !is_null($color_tela_id) &&
                !is_null($prelavado_id) &&
                !is_null($tipo_tela_id) &&
                !is_null($nevado_id) &&
                !is_null($focalizado_id)
            ) {
                $cotizaciones = $query->limit(500)->get();
            } else {
                $cotizaciones = $query->orderBy('id', 'desc')->limit(100)->get();
            }


            // $cotizaciones = Cotizacion::with('detalles')->orderBy('id', 'desc')->get();
            $valores = [
                'listado' => view('cotizacion.ajaxListado')->with(compact('cotizaciones'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function buscarCliente(Request $request)
    {
        if($request->ajax()){
            $cedula = $request->input('cedula');
            $cliente = User::where('cedula', $cedula)->first();
            if($cliente){
                $valores = [
                    'cliente' => $cliente
                ];
                $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
            }else{
                $data = Respuesta::error(null, "No se encontro al cliente");
            }
        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarCotizacion(Request $request)
    {
        if($request->ajax()){

            $cotizacion_id   = $request->input('cotizacion_id');
            $cedula          = $request->input('cedula');
            $cliente_id      = $request->input('cliente_id');
            $nombre          = $request->input('nombre');
            $ap_paterno      = $request->input('ap_paterno');
            $ap_materno      = $request->input('ap_materno');
            $cantidad_prenda = $request->input('cantidad_prenda');
            $peso_kg         = $request->input('peso_kg');
            $peso_gr         = $request->input('peso_gr');
            $prelavado_id    = $request->input('prelavado_id');
            $nevado_id       = $request->input('nevado_id');
            $focalizado_id   = $request->input('focalizado_id');

            $tipo_tela_id   = $request->input('tipo_tela_id');
            $color_tela_id   = $request->input('color_tela_id');
            $tipo_prenda_id   = $request->input('tipo_prenda_id');
            $descripcion   = $request->input('descripcion');

            $proceso_focalizado    = $request->input('proceso_focalizado');
            $proceso_id_focalizado = $request->input('proceso_id_focalizado');
            $precio_focalizado     = $request->input('precio_focalizado');
            $total_focalizado      = $request->input('total_focalizado');
            $proceso_planchado     = $request->input('proceso_planchado');
            $proceso_id_planchado  = $request->input('proceso_id_planchado');
            $precio_planchado      = $request->input('precio_planchado');
            $total_planchado       = $request->input('total_planchado');

            $mano_obra                     = $request->input('mano_obra');
            $servicio_basico               = $request->input('servicio_basico');
            $mantenimiento                 = $request->input('mantenimiento');
            $interes_bancario              = $request->input('interes_bancario');
            $porc_gananci                  = $request->input('porc_gananci');
            $precio_ven_pronosticado       = $request->input('precio_ven_pronosticado');
            $precio_venta_prosnosticado_s3 = $request->input('precio_venta_prosnosticado_s3');
            $costo_frost                   = $request->input('costo_frost');
            $costo_frost_foc               = $request->input('costo_frost_foc');
            $costo_frost_foc_cont          = $request->input('costo_frost_foc_cont');
            $precio_frost                  = $request->input('precio_frost');
            $precio_frost_foc              = $request->input('precio_frost_foc');
            $precio_frost_foc_cont         = $request->input('precio_frost_foc_cont');
            $utilidad_frost                = $request->input('utilidad_frost');
            $utilidad_frost_foc            = $request->input('utilidad_frost_foc');
            $utilidad_frost_foc_cont       = $request->input('utilidad_frost_foc_cont');
            $porcentaje_ganancia_s1        = $request->input('porcentaje_ganancia_s1');
            $porcentaje_ganancia_s2        = $request->input('porcentaje_ganancia_s2');
            $porcentaje_ganancia_s3        = $request->input('porcentaje_ganancia_s3');
            $utilidad_pronosticada_s1      = $request->input('utilidad_pronosticada_s1');
            $utilidad_pronosticada_s2      = $request->input('utilidad_pronosticada_s2');
            $utilidad_pronosticada_s3      = $request->input('utilidad_pronosticada_s3');
            $procesos                      = $request->input('procesos');
            $usuario                       = Auth::user();

            try {

                DB::beginTransaction();

                if ($cliente_id == 0) {
                    $clienteNew                     = new User();
                    $clienteNew->usuario_creador_id = $usuario->id;
                    $clienteNew->rol_id             = 3;
                    $clienteNew->nombres            = $nombre;
                    $clienteNew->ap_paterno         = $ap_paterno;
                    $clienteNew->ap_materno         = $ap_materno;
                    $clienteNew->cedula             = $cedula;
                    $clienteNew->name               = $nombre . " " . $ap_paterno . " " . $ap_materno;
                    $nombre_clean                   = str_replace(' ', '', strtolower(trim($nombre)));
                    $paterno_clean                  = str_replace(' ', '', strtolower(trim($ap_paterno)));
                    $materno_clean                  = str_replace(' ', '', strtolower(trim($ap_materno)));
                    $key_unico                      = substr(uniqid(), -4);
                    $clienteNew->email              = $nombre_clean . "." . $paterno_clean . "." . $materno_clean . "." . $key_unico . "@lavanderia-argentina.com";
                    $clienteNew->password           = "123456789";
                    $clienteNew->save();

                    $cliente_id = $clienteNew->id;
                }

                if ($cotizacion_id == 0) {
                    $cotizacion                     = new Cotizacion();
                    $cotizacion->usuario_creador_id = $usuario->id;
                } else {
                    $cotizacion                         = Cotizacion::find($cotizacion_id);
                    $cotizacion->usuario_modificador_id = $usuario->id;
                }

                // $cotizacion                               = new Cotizacion();
                $cotizacion->cliente_id                   = $cliente_id;
                $cotizacion->prelavado_id                 = $prelavado_id;
                $cotizacion->nevado_id                    = $nevado_id;

                $cotizacion->tipo_tela_id  = $tipo_tela_id;
                $cotizacion->color_tela_id = $color_tela_id;
                $cotizacion->prenda_id     = $tipo_prenda_id;
                $cotizacion->descripcion   = $descripcion;

                $cotizacion->focalizado_id                = $focalizado_id;
                $cotizacion->cantidad_prenda              = $cantidad_prenda;
                $cotizacion->peso_kg                      = $peso_kg;
                $cotizacion->peso_g                       = $peso_gr;
                $cotizacion->mano_obra                    = $mano_obra;
                $cotizacion->servicio_basico              = $servicio_basico;
                $cotizacion->mantenimiento                = $mantenimiento;
                $cotizacion->interes_bancario             = $interes_bancario;
                $cotizacion->porcentaje_ganacia           = $porc_gananci;
                $cotizacion->precio_venta_pronosticado    = $precio_ven_pronosticado;
                $cotizacion->precio_venta_pronosticado_s3 = $precio_venta_prosnosticado_s3;
                $cotizacion->costo_s1                     = $costo_frost;
                $cotizacion->costo_s2                     = $costo_frost_foc;
                $cotizacion->costo_s3                     = $costo_frost_foc_cont;
                $cotizacion->precio_s1                    = $precio_frost;
                $cotizacion->precio_s2                    = $precio_frost_foc;
                $cotizacion->precio_s3                    = $precio_frost_foc_cont;
                $cotizacion->utilidad_s1                  = $utilidad_frost;
                $cotizacion->utilidad_s2                  = $utilidad_frost_foc;
                $cotizacion->utilidad_s3                  = $utilidad_frost_foc_cont;
                $cotizacion->porcentaje_ganancia_s1       = $porcentaje_ganancia_s1;
                $cotizacion->porcentaje_ganancia_s2       = $porcentaje_ganancia_s2;
                $cotizacion->porcentaje_ganancia_s3       = $porcentaje_ganancia_s3;
                $cotizacion->utilidad_pronosticada_s1     = $utilidad_pronosticada_s1;
                $cotizacion->utilidad_pronosticada_s2     = $utilidad_pronosticada_s2;
                $cotizacion->utilidad_pronosticada_s3     = $utilidad_pronosticada_s3;
                $cotizacion->save();

                if ($cotizacion_id != 0) {
                    // PRIMERO ELIMINAMOS LO REGISTRADO
                    $detalles = $cotizacion->detalles;
                    foreach ($detalles as $detalle) {
                        $detalle->delete();
                    }
                }

                foreach ($procesos as $proceso) {
                    $procesoId = $proceso['proceso_id'];
                    $ordenProceso = $proceso['orden_proceso'] ?? null;
                    foreach ($proceso['productos'] as $producto) {

                        $cotizacionDetalle                     = new CotizacionDetalle();
                        $cotizacionDetalle->usuario_creador_id = $usuario->id;
                        $cotizacionDetalle->cotizacion_id      = $cotizacion->id;
                        $cotizacionDetalle->tipo_proceso_id    = $procesoId;
                        $cotizacionDetalle->producto_id        = $producto['producto_id'];

                        $cotizacionDetalle->orden_proceso      = $ordenProceso;
                        $cotizacionDetalle->orden_producto     = $producto['orden_producto'] ?? null;

                        $cotizacionDetalle->porcentaje         = $producto['porcentaje'];
                        $cotizacionDetalle->cantidad           = $producto['cantidad'];
                        $cotizacionDetalle->total              = $producto['total'];
                        $cotizacionDetalle->save();
                    }
                }

                //################### PARA FOCALIZADO ###################
                if ($precio_focalizado > 0 && $total_focalizado > 0) {
                    $cotizacionDetalle                     = new CotizacionDetalle();
                    $cotizacionDetalle->usuario_creador_id = $usuario->id;
                    $cotizacionDetalle->cotizacion_id      = $cotizacion->id;
                    $cotizacionDetalle->tipo_proceso_id    = $proceso_id_focalizado;
                    $cotizacionDetalle->cantidad           = $precio_focalizado;
                    $cotizacionDetalle->total              = $total_focalizado;
                    $cotizacionDetalle->save();
                }

                //################### PARA PLANCHADO ###################
                if ($precio_planchado > 0 && $total_planchado > 0) {
                    $cotizacionDetalle                     = new CotizacionDetalle();
                    $cotizacionDetalle->usuario_creador_id = $usuario->id;
                    $cotizacionDetalle->cotizacion_id      = $cotizacion->id;
                    $cotizacionDetalle->tipo_proceso_id    = $proceso_id_planchado;
                    $cotizacionDetalle->cantidad           = $precio_planchado;
                    $cotizacionDetalle->total              = $total_planchado;
                    $cotizacionDetalle->save();
                }

                DB::commit();

                $data = Respuesta::success(null, "Datos Obtenidos correctamente");

            } catch (\Exception $e) {
                DB::rollBack();

                $data = Respuesta::error(null, "Error al guardar: ".$e->getMessage());
            }
        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function reportePdf($id) {
        $cotizacion = Cotizacion::with([
            'cliente',
            'prelavado',
            'nevado',
            'focalizado'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('cotizacion.pdf.reportePdf', compact('cotizacion'));

        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Cotizacion_' . $cotizacion->id . '.pdf');
    }

    public function reporteExcel($id)
    {
        $cotizacion = Cotizacion::with([
            'cliente',
            'prelavado',
            'nevado',
            'focalizado',
            'detalles.producto',
            'detalles.proceso'
        ])->findOrFail($id);

        $fileName = "Cotizacion_{$id}.xlsx";

        $libro = new Spreadsheet();
        $hoja = $libro->getActiveSheet();

        // =========================
        // ENCABEZADO
        // =========================

        $hoja->mergeCells('A1:E1');
        $hoja->setCellValue('A1', "COTIZACION # {$cotizacion->id}");

        $hoja->mergeCells('A2:E2');
        $hoja->setCellValue(
            'A2',
            "CLIENTE: " .
                $cotizacion->cliente->nombres . " " .
                $cotizacion->cliente->ap_paterno . " " .
                $cotizacion->cliente->ap_materno
        );

        $hoja->mergeCells('A3:E3');
        $hoja->setCellValue('A3', "FECHA: " . $cotizacion->created_at);


        $hoja->mergeCells('A4:E4');
        $hoja->setCellValue('A4', "CANTIDAD PRENDAS: " . (int)$cotizacion->cantidad_prenda);


        $hoja->mergeCells('A5:E5');
        $hoja->setCellValue(
            'A5',
            "PESO KG: " . $cotizacion->peso_kg .
                " | PESO GR: " . $cotizacion->peso_g
        );


        $hoja->mergeCells('A6:E6');
        $hoja->setCellValue(
            'A6',
            "PRELAVADO: " . $cotizacion->prelavado?->nombre .
                " | NEVADO: " . $cotizacion->nevado?->nombre
        );


        $hoja->mergeCells('A7:E7');
        $hoja->setCellValue(
            'A7',
            "FOCALIZADO: " . $cotizacion->focalizado?->nombre
        );


        $hoja->mergeCells('A8:E8');
        $hoja->setCellValue(
            'A8',
            "TIPO TELA: " . $cotizacion->tipo_tela .
                " | COLOR TELA: " . $cotizacion->color_tela .
                " | TIPO PRENDA: " . $cotizacion->tipo_prenda
        );


        $hoja->mergeCells('A9:E9');
        $hoja->setCellValue(
            'A9',
            "DESCRIPCIÓN: " . $cotizacion->descripcion
        );



        // =========================
        // TITULO TABLA
        // =========================

        $startRow = 11;


        $hoja->setCellValue("A{$startRow}", "PROCESO");
        $hoja->setCellValue("B{$startRow}", "PRODUCTO");
        $hoja->setCellValue("C{$startRow}", "PORCENTAJE");
        $hoja->setCellValue("D{$startRow}", "CANTIDAD");
        $hoja->setCellValue("E{$startRow}", "TOTAL");


        $hoja->getStyle("A{$startRow}:E{$startRow}")
            ->applyFromArray([
                'font' => [
                    'bold' => true
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFEFEFEF'
                    ]
                ]
            ]);



        // =========================
        // DETALLES
        // =========================

        $row = $startRow + 1;


        $tipoProcesos = $cotizacion->detalles
            ->groupBy('tipo_proceso_id');


        foreach ($tipoProcesos as $tipoProceso) {


            $cat = count($tipoProceso);
            $first = true;


            foreach ($tipoProceso as $detalle) {


                if ($first) {
                    $hoja->setCellValue(
                        "A{$row}",
                        $detalle->proceso->nombre
                    );


                    $hoja->mergeCells(
                        "A{$row}:A" . ($row + $cat - 1)
                    );


                    $first = false;
                }


                $hoja->setCellValue(
                    "B{$row}",
                    $detalle->producto?->nombre
                );


                $hoja->setCellValue(
                    "C{$row}",
                    $detalle->porcentaje
                );


                $hoja->setCellValue(
                    "D{$row}",
                    $detalle->cantidad
                );


                $hoja->setCellValue(
                    "E{$row}",
                    $detalle->total
                );


                $row++;
            }
        }



        // =========================
        // RESUMEN
        // =========================

        $row++;


        $hoja->setCellValue("A{$row}", "MANO DE OBRA");
        $hoja->setCellValue("B{$row}", $cotizacion->mano_obra);


        $hoja->setCellValue("C{$row}", "SERVICIO BASICO");
        $hoja->setCellValue("D{$row}", $cotizacion->servicio_basico);


        $hoja->setCellValue("E{$row}", "MANTENIMIENTO " . $cotizacion->mantenimiento);


        $row++;


        $hoja->setCellValue("A{$row}", "INTERES BANCARIO");
        $hoja->setCellValue("B{$row}", $cotizacion->interes_bancario);


        $hoja->setCellValue("C{$row}", "% GANANCIA");
        $hoja->setCellValue("D{$row}", $cotizacion->porcentaje_ganacia);


        $hoja->setCellValue(
            "E{$row}",
            "PRECIO VENTA PRONOSTICADO S3: " . $cotizacion->precio_venta_pronosticado_s3
        );



        $row++;



        // =========================
        // COSTO
        // =========================

        $hoja->setCellValue("A{$row}", "COSTO");
        $hoja->setCellValue("B{$row}", $cotizacion->costo_s1);
        $hoja->setCellValue("C{$row}", $cotizacion->costo_s2);
        $hoja->setCellValue("D{$row}", $cotizacion->costo_s3);



        $row++;



        // =========================
        // PRECIO
        // =========================

        $hoja->setCellValue("A{$row}", "PRECIO");
        $hoja->setCellValue("B{$row}", $cotizacion->precio_s1);
        $hoja->setCellValue("C{$row}", $cotizacion->precio_s2);
        $hoja->setCellValue("D{$row}", $cotizacion->precio_s3);



        $row++;



        // =========================
        // UTILIDAD
        // =========================

        $hoja->setCellValue("A{$row}", "UTILIDAD");
        $hoja->setCellValue("B{$row}", $cotizacion->utilidad_s1);
        $hoja->setCellValue("C{$row}", $cotizacion->utilidad_s2);
        $hoja->setCellValue("D{$row}", $cotizacion->utilidad_s3);



        $row++;



        // =========================
        // % GANANCIA
        // =========================

        $hoja->setCellValue("A{$row}", "% GANANCIA");
        $hoja->setCellValue("B{$row}", $cotizacion->porcentaje_ganancia_s1);
        $hoja->setCellValue("C{$row}", $cotizacion->porcentaje_ganancia_s2);
        $hoja->setCellValue("D{$row}", $cotizacion->porcentaje_ganancia_s3);



        $row++;



        // =========================
        // UTILIDAD PRONOSTICADA
        // =========================

        $hoja->setCellValue("A{$row}", "UTILIDAD PRONOSTICADA");
        $hoja->setCellValue("B{$row}", $cotizacion->utilidad_pronosticada_s1);
        $hoja->setCellValue("C{$row}", $cotizacion->utilidad_pronosticada_s2);
        $hoja->setCellValue("D{$row}", $cotizacion->utilidad_pronosticada_s3);



        // =========================
        // BORDES
        // =========================

        $hoja->getStyle("A1:E{$row}")
            ->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ],
                'alignment' => [
                    'wrapText' => true
                ]
            ]);



        foreach (range('A', 'E') as $col) {
            $hoja->getColumnDimension($col)
                ->setAutoSize(true);
        }



        // =========================
        // DOWNLOAD
        // =========================

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename=\"{$fileName}\"");
        header('Cache-Control: max-age=0');


        $writer = new Xlsx($libro);
        $writer->save('php://output');

        exit;
    }

    public function eliminarCotizacion(Request $request){

        if($request->ajax()){

            $cotizacion_id = $request->input('cotizacion');
            $usuario = Auth::user();

            $cotizacion = Cotizacion::find($cotizacion_id);
            $cotizacion->usuario_eliminador_id = $usuario->id;
            $cotizacion->save();

            $detallesCotizacion =$cotizacion->detalles;

            foreach ($detallesCotizacion as $key => $cotizacionDetalle) {
                $cotizacionDetalle->usuario_eliminador_id = $usuario->id;
                $cotizacionDetalle->save();

                CotizacionDetalle::destroy($cotizacionDetalle->id);
            }

            Cotizacion::destroy($cotizacion->id);

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }
}
