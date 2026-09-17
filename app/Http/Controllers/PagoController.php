<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Factura;
use App\Models\Pago;
use App\Models\SubCategoria;
use App\Models\Sucursal;
use App\Models\User;
use App\Utils\Respuesta;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
                $query->where('sucursal_id', $sucursal_id);
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

    public function formularioDecuentoAdicional(Request $request){

        if($request->ajax()){

            $factura_id = $request->input('factura_id');
            $factura = Factura::find($factura_id);

            $pagado = pago::where('factura_id', $factura_id)
                            ->where('estado', 'INGRESO')
                            ->sum('monto');

            $valores = [
                'formulario' => view('pago.formularioDecuentoAdicional')->with(compact('factura', 'pagado'))->render()
            ];
            $data = Respuesta::success($valores, "Datos obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }

    public function guardarDescuentoAdicional(Request $request){

        if($request->ajax()){

            $usuario                         = Auth::user();
            $factura_id                      = $request->input('factura_id');
            $descuento_adicional             = $request->input('descuento_adicional');
            $descripcion_descuento_adicional = $request->input('descripcion_descuento_adicional');

            $factura                         = Factura::find($factura_id);
            $factura->usuario_modificador_id = $usuario->id;
            $factura->descuento_adicional    = $descuento_adicional;
            $factura->descripcion            = $descripcion_descuento_adicional;
            $factura->save();

            $data = Respuesta::success(null, "Datos obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;

    }

    public function generaExcelPago(Request $request){

        if($request->ajax()){

            // dd($request->all());

            $sucursal_id = $request->input('sucursal_id');
            $fecha_ini   = $request->input('fecha_ini');
            $fecha_fin   = $request->input('fecha_fin');
            $usuario_id  = $request->input('usuario_busqueda_id');

            $query = Pago::select();

            if ($sucursal_id != null) {
                $query->where('sucursal_id', $sucursal_id);
            }

            if ($fecha_ini != null && $fecha_fin != null) {
                $query->where('fecha', '>=', $fecha_ini . ' 00:00:00')
                    ->where('fecha', '<=', $fecha_fin . ' 23:59:59');
            }

            if ($usuario_id != null) {
                $query->where('usuario_creador_id', $usuario_id);
            }

            $pagos = $query->orderBy('id', 'desc')->get();

            // generacion del excel
            $fileName = 'Pagos.xlsx';
            $libro = new Spreadsheet();
            $hoja = $libro->getActiveSheet();

            // Ajustar ancho de columnas
            $hoja->getColumnDimension('A')->setWidth(15); // N°
            $hoja->getColumnDimension('B')->setWidth(25); // SUCURSAL
            $hoja->getColumnDimension('C')->setWidth(25); // FECHA
            $hoja->getColumnDimension('D')->setWidth(15); // DESCRIPCION
            $hoja->getColumnDimension('E')->setWidth(20); // CATEGORIA PAGO
            $hoja->getColumnDimension('F')->setWidth(15); // SUB CATEGORIA/REC
            $hoja->getColumnDimension('G')->setWidth(20); // TIPO PAGO EFECTIVO
            $hoja->getColumnDimension('H')->setWidth(20); // FAC/REC DEPOSITO
            $hoja->getColumnDimension('I')->setWidth(20); // MONTO EFECTIVO
            $hoja->getColumnDimension('J')->setWidth(20); // MONTO DEPOSITO
            $hoja->getColumnDimension('K')->setWidth(20); // ESTADO
            $hoja->getColumnDimension('L')->setWidth(20); // USUARIO
            $hoja->getColumnDimension('M')->setWidth(20); // VIGENCIA

            // Añadir datos a la hoja de cálculo
            $hoja->setCellValue('A1', "REPORTE CENTRALIZADO DE PAGOS");
            $hoja->setCellValue('A2', "LISTADO DE PAGOS");
            $hoja->setCellValue('A3', "FECHA DE REPORTE: ".date('d/m/Y H:i:s'));

            $hoja->setCellValue('A4', "N°");
            $hoja->setCellValue('B4', "SUCURSAL");
            $hoja->setCellValue('C4', "FECHA");
            $hoja->setCellValue('D4', "DESCRIPCION");
            $hoja->setCellValue('E4', "CATEGORIA");
            $hoja->setCellValue('F4', "SUB CATEGORIA");
            $hoja->setCellValue('G4', "TIPO PAGO");
            $hoja->setCellValue('H4', "FAC/REC");
            $hoja->setCellValue('I4', "MONTO EFECTIVO");
            $hoja->setCellValue('J4', "MONTO DEPOSITO");
            $hoja->setCellValue('K4', "ESTADO");
            $hoja->setCellValue('L4', "USUARIO");
            $hoja->setCellValue('M4', "VIGENCIA");

            $encabezadoStyle = [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];

            $hoja->mergeCells('A1:M1');
            $hoja->mergeCells('A2:M2');
            $hoja->mergeCells('A3:M3');

            $hoja->getStyle('A1')->applyFromArray($encabezadoStyle);
            $hoja->getStyle('A2')->applyFromArray($encabezadoStyle);
            $hoja->getStyle('A3')->applyFromArray($encabezadoStyle);

            // Aplicar márgenes y formato a los encabezados
            $encabezadoStyle = [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFFFE0B2', // Color de fondo
                    ],
                ],
            ];
            $hoja->getStyle('A4:M4')->applyFromArray($encabezadoStyle);

            $contadorInicio              = 5;

            foreach ($pagos  as $key => $pago) {

                $hoja->setCellValue('A' . $contadorInicio, ($key + 1));
                $hoja->setCellValue('B' . $contadorInicio, $pago->sucursal?->nombre);
                $hoja->setCellValue('C' . $contadorInicio, $pago->fecha);
                $hoja->setCellValue('D' . $contadorInicio, $pago->descripcion);

                $hoja->setCellValue('E' . $contadorInicio, $pago->subCategoria?->Categoria?->nombre);
                $hoja->setCellValue('F' . $contadorInicio, $pago->subCategoria?->nombre);

                $hoja->setCellValue('G' . $contadorInicio, $pago->tipo_pago);
                $hoja->setCellValue('H' . $contadorInicio, $pago->factura ? ($pago->factura->numero_factura) : "");

                if($pago->estado === 'INGRESO')
                    $m = ($pago->tipo_pago === 'EFECTIVO') ?$pago->monto : 0;
                elseif($pago->estado === 'SALIDA')
                    $m = ($pago->tipo_pago === 'EFECTIVO') ?$pago->monto : 0;

                $hoja->setCellValue('I' . $contadorInicio, $m);

                if($pago->estado === 'INGRESO')
                    $m1 = ($pago->tipo_pago === 'TRANSFERENCIA' || $pago->tipo_pago === 'QR') ?$pago->monto : 0;
                elseif($pago->estado === 'SALIDA')
                    $m1 = ($pago->tipo_pago === 'TRANSFERENCIA' || $pago->tipo_pago === 'QR') ?$pago->monto : 0;

                $hoja->setCellValue('J' . $contadorInicio, $m1);

                $hoja->setCellValue('K' . $contadorInicio, $pago->estado);
                $hoja->setCellValue('L' . $contadorInicio, $pago->usuario->name);
                $hoja->setCellValue('M' . $contadorInicio, is_null($pago->fecha_anulacion) ? 'Vigente' : 'Anulado' );

                $contadorInicio++;
            }

            // Aplicar bordes a las celdas de datos
            $hoja->getStyle('A5:K' . ($contadorInicio - 1))->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ]);

            // Establecer los encabezados para forzar la descarga
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $fileName . '"');
            header('Cache-Control: max-age=0');

            // Guardar el archivo
            $writer = new Xlsx($libro);
            $writer->save('php://output');
            exit;

        }else{
            $data = Respuesta::error(null, "Error en registro de datos.");
        }
        return $data;
    }

    public function comprobantePago(Request $request, $pago_id){

        $pago = Pago::find($pago_id);

        $html = View::make('pago.pdf.comprobantePago', compact(['pago']))->render();
        $dompdf = new Dompdf();
        $dompdf->setPaper(array(0,0,300.00,504.00), 'landscape');//cambio orientacion de la hoja
        $dompdf->loadHtml($html);
        $dompdf->render();
        //return $dompdf->stream('Reporte_Ingresos.pdf');

        return response($dompdf->output())
        ->header('Content-Type', 'application/pdf')
        ->header('Content-Disposition', 'inline; filename=Cotizacion.pdf');

        // dd($request->all(), $pago_id);

    }
}
