<?php

use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\CaracteristicaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ColorTelaController;
use App\Http\Controllers\ConfiguracionPersonalController;
use App\Http\Controllers\ControlPersonalController;
use App\Http\Controllers\DeudaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FocalizadoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\NombreTelaController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PagosPersonalController;
use App\Http\Controllers\PrelavadoController;
use App\Http\Controllers\PrendaController;
use App\Http\Controllers\ProduccionPagoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\TipoProcesoController;
use App\Http\Controllers\TipoTelaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProcesosController;
use App\Http\Controllers\SolicitudController;
use App\Http\Controllers\NevadoController;
use App\Http\Controllers\SubCategoriaController;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect('home');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('home');

    // ROL
    Route::prefix('/rol')->group(function () {
        Route::get('/listado', [RolController::class, 'listado'])->name('rol.listado');
        Route::post('/ajaxListado', [RolController::class, 'ajaxListado'])->name('rol.ajaxListado');
        Route::post('/guardarRol', [RolController::class, 'guardarRol'])->name('rol.guardarRol');
        Route::post('/eliminarRol', [RolController::class, 'eliminarRol'])->name('rol.eliminarRol');
    });

    // CATEGORIA
    Route::prefix('/categoria')->group(function () {
        Route::get('/listado', [CategoriaController::class, 'listado'])->name('categoria.listado');
        Route::post('/ajaxListado', [CategoriaController::class, 'ajaxListado'])->name('categoria.ajaxListado');
        Route::post('/guardar', [CategoriaController::class, 'guardar'])->name('categoria.guardar');
        Route::post('/eliminar', [CategoriaController::class, 'eliminar'])->name('categoria.eliminar');
    });

    //  SUB CATEGORIA
    Route::prefix('/subCategoria')->group(function () {
        Route::get('/listado', [SubCategoriaController::class, 'listado'])->name('subCategoria.listado');
        Route::post('/ajaxListado', [SubCategoriaController::class, 'ajaxListado'])->name('subCategoria.ajaxListado');
        Route::post('/guardar', [SubCategoriaController::class, 'guardar'])->name('subCategoria.guardar');
        Route::post('/eliminar', [SubCategoriaController::class, 'eliminar'])->name('subCategoria.eliminar');
    });

    // USUARIO
    Route::prefix('/user')->group(function () {
        Route::get('/listado', [UserController::class, 'listado'])->name('user.listado');
        Route::post('/ajaxListado', [UserController::class, 'ajaxListado'])->name('user.ajaxListado');
        Route::post('/guardarUser', [UserController::class, 'guardarUser'])->name('user.guardarUser');
        Route::post('/eliminarUser', [UserController::class, 'eliminarUser'])->name('user.eliminarUser');

        Route::get('/control-personal/user/{id}', [UserController::class, 'getUser']);
    });
    // CLIENTE
    Route::prefix('/cliente')->group(function () {
        Route::get('/listado', [ClienteController::class, 'listado'])->name('cliente.listado');
        Route::post('/ajaxListado', [ClienteController::class, 'ajaxListado'])->name('cliente.ajaxListado');
        Route::post('/guardarCliente', [ClienteController::class, 'guardarCliente'])->name('cliente.guardarCliente');
        Route::post('/eliminarCliente', [ClienteController::class, 'eliminarCliente'])->name('cliente.eliminarCliente');
        Route::get('/verVenta/{cliente_id}', [ClienteController::class, 'verVenta'])->name('cliente.verVenta');
    });
    // PRENDA
    Route::prefix('/prenda')->group(function () {
        Route::get('/listado', [PrendaController::class, 'listado'])->name('prenda.listado');
        Route::post('/ajaxListado', [PrendaController::class, 'ajaxListado'])->name('prenda.ajaxListado');
        Route::post('/guardarPrenda', [PrendaController::class, 'guardarPrenda'])->name('prenda.guardarPrenda');
        Route::post('/eliminarPrenda', [PrendaController::class, 'eliminarPrenda'])->name('prenda.eliminarPrenda');
    });
    // TIPO TELA
    Route::prefix('/tipo_tela')->group(function () {
        Route::get('/listado', [TipoTelaController::class, 'listado'])->name('tipo_tela.listado');
        Route::post('/ajaxListado', [TipoTelaController::class, 'ajaxListado'])->name('tipo_tela.ajaxListado');
        Route::post('/guardarTipoTela', [TipoTelaController::class, 'guardarTipoTela'])->name('tipo_tela.guardarTipoTela');
        Route::post('/eliminarTipoTela', [TipoTelaController::class, 'eliminarTipoTela'])->name('tipo_tela.eliminarTipoTela');
    });
    // CARACTERISTICA
    Route::prefix('/caracteristica')->group(function () {
        Route::get('/listado', [CaracteristicaController::class, 'listado'])->name('caracteristica.listado');
        Route::post('/ajaxListado', [CaracteristicaController::class, 'ajaxListado'])->name('caracteristica.ajaxListado');
        Route::post('/guardarCaracteristica', [CaracteristicaController::class, 'guardarCaracteristica'])->name('caracteristica.guardarCaracteristica');
        Route::post('/eliminarCaracteristica', [CaracteristicaController::class, 'eliminarCaracteristica'])->name('caracteristica.eliminarCaracteristica');
    });
    // SUCURSAL
    Route::prefix('/sucursal')->group(function () {
        Route::get('/listado', [SucursalController::class, 'listado'])->name('sucursal.listado');
        Route::post('/ajaxListado', [SucursalController::class, 'ajaxListado'])->name('sucursal.ajaxListado');
        Route::post('/guardarSucursal', [SucursalController::class, 'guardarSucursal'])->name('sucursal.guardarSucursal');
        Route::post('/eliminarSucursal', [SucursalController::class, 'eliminarSucursal'])->name('sucursal.eliminarSucursal');
    });
    // PROVEEDOR
    Route::prefix('/proveedor')->group(function () {
        Route::get('/listado', [ProveedorController::class, 'listado'])->name('proveedor.listado');
        Route::post('/ajaxListado', [ProveedorController::class, 'ajaxListado'])->name('proveedor.ajaxListado');
        Route::post('/guardarProveedor', [ProveedorController::class, 'guardarProveedor'])->name('proveedor.guardarProveedor');
        Route::post('/eliminarProveedor', [ProveedorController::class, 'eliminarProveedor'])->name('proveedor.eliminarProveedor');
    });
    // PRODUCTO
    Route::prefix('/producto')->group(function () {
        Route::get('/listado', [ProductoController::class, 'listado'])->name('producto.listado');
        Route::post('/ajaxListado', [ProductoController::class, 'ajaxListado'])->name('producto.ajaxListado');
        Route::post('/guardarProducto', [ProductoController::class, 'guardarProducto'])->name('producto.guardarProducto');
        Route::post('/eliminarProducto', [ProductoController::class, 'eliminarProducto'])->name('producto.eliminarProducto');
    });
    // MAQUINARIAS
    Route::prefix('/maquinaria')->group(function () {
        Route::get('/listado', [MaquinariaController::class, 'listado'])->name('maquinaria.listado');
        Route::post('/ajaxListado', [MaquinariaController::class, 'ajaxListado'])->name('maquinaria.ajaxListado');
        Route::post('/guardarMaquinaria', [MaquinariaController::class, 'guardarMaquinaria'])->name('maquinaria.guardarMaquinaria');
        Route::post('/eliminarMaquinaria', [MaquinariaController::class, 'eliminarMaquinaria'])->name('maquinaria.eliminarMaquinaria');
        Route::post('/info', [MaquinariaController::class, 'info'])->name('maquinaria.info');
    });
    // color TELA
    Route::prefix('/color_tela')->group(function () {
        Route::get('/listado', [ColorTelaController::class, 'listado'])->name('color_tela.listado');
        Route::post('/ajaxListado', [ColorTelaController::class, 'ajaxListado'])->name('color_tela.ajaxListado');
        Route::post('/guardarColorTela', [ColorTelaController::class, 'guardarColorTela'])->name('color_tela.guardarColorTela');
        Route::post('/eliminarColorTela', [ColorTelaController::class, 'eliminarColorTela'])->name('color_tela.eliminarColorTela');
    });
    // NOMBRE TELA
    Route::prefix('/nombre_tela')->group(function () {
        Route::get('/listado', [NombreTelaController::class, 'listado'])->name('nombre_tela.listado');
        Route::post('/ajaxListado', [NombreTelaController::class, 'ajaxListado'])->name('nombre_tela.ajaxListado');
        Route::post('/guardarNombreTela', [NombreTelaController::class, 'guardarNombreTela'])->name('nombre_tela.guardarNombreTela');
        Route::post('/eliminarNombreTela', [NombreTelaController::class, 'eliminarNombreTela'])->name('nombre_tela.eliminarNombreTela');
    });
    // TIPO PROCESO
    Route::prefix('/tipo_proceso')->group(function () {
        Route::get('/listado', [TipoProcesoController::class, 'listado'])->name('tipo_proceso.listado');
        Route::post('/ajaxListado', [TipoProcesoController::class, 'ajaxListado'])->name('tipo_proceso.ajaxListado');
        Route::post('/guardarTipoProceso', [TipoProcesoController::class, 'guardarTipoProceso'])->name('tipo_proceso.guardarTipoProceso');
        Route::post('/eliminarTipoProceso', [TipoProcesoController::class, 'eliminarTipoProceso'])->name('tipo_proceso.eliminarTipoProceso');

    });
    // PRELAVADO
    Route::prefix('/prelavado')->group(function () {
        Route::get('/listado', [PrelavadoController::class, 'listado'])->name('prelavado.listado');
        Route::post('/ajaxListado', [PrelavadoController::class, 'ajaxListado'])->name('prelavado.ajaxListado');
        Route::post('/guardarPrelavado', [PrelavadoController::class, 'guardarPrelavado'])->name('prelavado.guardarPrelavado');
        Route::post('/eliminarPrelavado', [PrelavadoController::class, 'eliminarPrelavado'])->name('prelavado.eliminarPrelavado');
    });
    // FOCALIZADO
    Route::prefix('/focalizado')->group(function () {
        Route::get('/listado', [FocalizadoController::class, 'listado'])->name('focalizado.listado');
        Route::post('/ajaxListado', [FocalizadoController::class, 'ajaxListado'])->name('focalizado.ajaxListado');
        Route::post('/guardarFocalizado', [FocalizadoController::class, 'guardarFocalizado'])->name('focalizado.guardarFocalizado');
        Route::post('/eliminarFocalizado', [FocalizadoController::class, 'eliminarFocalizado'])->name('focalizado.eliminarFocalizado');
    });
    // MOVIMIENTO
    Route::prefix('/movimiento')->group(function () {
        Route::get('/listado', [MovimientoController::class, 'listado'])->name('movimiento.listado');
        Route::get('/ajaxListado', [MovimientoController::class, 'ajaxListado'])->name('movimiento.ajaxListado');
        Route::post('/guardarIngreso', [MovimientoController::class, 'guardarIngreso'])->name('movimiento.guardarIngreso');
        Route::post('/guardarSalida', [MovimientoController::class, 'guardarSalida'])->name('movimiento.guardarSalida');
        Route::post('/sacarTipoIngreso', [MovimientoController::class, 'sacarTipoIngreso'])->name('movimiento.sacarTipoIngreso');

        //Route::post('/guardarMovimiento', [MovimientoController::class, 'guardarMovimiento'])->name('movimiento.guardarMovimiento');
    });

    // FACTURA
    Route::prefix('/factura')->group(function () {
        Route::get('/formulario', [FacturaController::class, 'formulario'])->name('factura.formulario');
        Route::post('/recepcionar', [FacturaController::class, 'recepcionar'])->name('factura.recepcionar');
        Route::get('/listado', [FacturaController::class, 'listado'])->name('factura.listado');
        Route::get('/recibo/{factura_id}', [FacturaController::class, 'recibo'])->name('factura.recibo');
        Route::post('/ajaxListadoFacturas', [FacturaController::class, 'ajaxListadoFacturas'])->name('factura.ajaxListadoFacturas');
        Route::get('/detalle/{factura_id}', [FacturaController::class, 'detalle'])->name('factura.detalle');
        Route::post('/anularRecibo', [FacturaController::class, 'anularRecibo']);
        Route::post('/agregarNuevoOrdenTrabajo', [FacturaController::class, 'agregarNuevoOrdenTrabajo']);
        //
        Route::get('/facturas-estado-null', [FacturaController::class, 'getFacturasNull'])->name('factura.estadoNull');
        Route::get('/ots', [FacturaController::class, 'getOTs'])->name('factura.obtenerOTs');
        Route::post('/obtenerProductosAprobados', [FacturaController::class, 'obtenerProductosAprobados'])->name('factura.obtenerProductosAprobados');
        Route::post('/enviarArchivar', [FacturaController::class, 'enviarArchivar'])->name('factura.enviarArchivar');
    });

    //PAGO
    Route::prefix('/pago')->group(function () {
        Route::post('/guardarTipoIngresoSalida', [PagoController::class, 'guardarTipoIngresoSalida']);
        Route::get('/listado', [PagoController::class, 'listado'])->name('pago.listado');
        Route::post('/ajaxListado', [PagoController::class, 'ajaxListado'])->name('pago.ajaxListado');
        Route::get('/listadoDeuda', [PagoController::class, 'listadoDeuda'])->name('pago.listadoDeuda');
        Route::post('/ajaxListadoDeuda', [PagoController::class, 'ajaxListadoDeuda'])->name('pago.ajaxListadoDeuda');
        Route::post('/ajaxFormPagoDeuda', [PagoController::class, 'ajaxFormPagoDeuda'])->name('pago.ajaxFormPagoDeuda');
        Route::post('/guardarPagoDeuda', [PagoController::class, 'guardarPagoDeuda'])->name('pago.guardarPagoDeuda');
        Route::post('/ajaxDescargarReportePago', [PagoController::class, 'ajaxDescargarReportePago'])->name('pago.ajaxDescargarReportePago');
        Route::post('/formularioDecuentoAdicional', [PagoController::class, 'formularioDecuentoAdicional'])->name('pago.formularioDecuentoAdicional');
        Route::post('/guardarDescuentoAdicional', [PagoController::class, 'guardarDescuentoAdicional'])->name('pago.guardarDescuentoAdicional');
        Route::get('/comprobantePago/{pago_id}', [PagoController::class, 'comprobantePago'])->name('pago.comprobantePago');
        Route::post('/generaExcelPago', [PagoController::class, 'generaExcelPago'])->name('pago.generaExcelPago');
    });

    //PROCESOS
    Route::prefix('/procesos')->group(function () {

        Route::get('/listado', [ProcesosController::class, 'listado'])->name('procesos.listado');
        Route::post('/ajaxListado', [ProcesosController::class, 'ajaxListado'])->name('procesos.ajaxListado');

        Route::get('/lista-productos', [ProcesosController::class, 'listaProductos'])
            ->name('procesos.listaProductos');

        Route::get('/lista-tipos-proceso', [ProcesosController::class, 'listaTiposProceso'])
            ->name('procesos.listaTiposProceso');

        // web.php
        Route::post('/info-maquinaria', [ProcesosController::class, 'infoMaquinaria'])->name('procesos.infoMaquinaria');
        Route::post('/actualizar-estados', [ProcesosController::class, 'actualizarEstados'])->name('procesos.actualizarEstados');

        Route::get('/lista-ots', [ProcesosController::class, 'listaOTs'])->name('procesos.listaOTs');
        Route::get('/detalle-ot/{ot}', [ProcesosController::class, 'detalleOT']);

        Route::post('/finalizar-ot', [ProcesosController::class, 'finalizarOT'])->name('procesos.finalizarOT');
        Route::get('/productos-movimientos', [ProcesosController::class, 'productosMovimientos'])->name('procesos.productosMovimientos');

        //  Route::get('/ots-por-factura', [ProcesosController::class, 'listaOTsPorFactura'])->name('procesos.listaOTsPorFactura');
        Route::get('/ots-por-factura', [ProcesosController::class, 'otsPorFactura'])->name('procesos.otsPorFactura');

        Route::get('/productos-aprobados-por-ot/{ot_id}', [ProcesosController::class, 'productosAprobadosPorOT'])->name('procesos.productosAprobadosPorOT');
        //////////////////
        Route::post('/guardar-listado', [ProcesosController::class, 'guardarListado'])->name('procesos.guardarListado');
        Route::get('/obtener-ot/{id}', [ProcesosController::class, 'obtenerOT'])->name('procesos.obtenerOT')->middleware('auth');
        Route::post('/guardar-proceso-ot', [ProcesosController::class, 'guardarProcesoOT'])->name('procesos.guardarProcesoOT');

        Route::post('/verProcesoEnMarchaMaquina', [ProcesosController::class, 'verProcesoEnMarchaMaquina'])->name('procesos.verProcesoEnMarchaMaquina');
        Route::post('/finalizarProceso', [ProcesosController::class, 'finalizarProceso'])->name('procesos.finalizarProceso');
        Route::post('/buscarSolicitudesProductoSoloAgua', [ProcesosController::class, 'buscarSolicitudesProductoSoloAgua'])->name('procesos.buscarSolicitudesProductoSoloAgua');
        Route::post('/ajaxListadoMaquinas', [ProcesosController::class, 'ajaxListadoMaquinas'])->name('procesos.ajaxListadoMaquinas');
        Route::post('/sacarSolicitudesAgrupados', [ProcesosController::class, 'sacarSolicitudesAgrupados'])->name('procesos.sacarSolicitudesAgrupados');
        Route::post('/sacarPesoTotalSolicitudAgrupado', [ProcesosController::class, 'sacarPesoTotalSolicitudAgrupado'])->name('procesos.sacarPesoTotalSolicitudAgrupado');
        Route::post('/GuardarSolicitudAgrupado', [ProcesosController::class, 'GuardarSolicitudAgrupado'])->name('procesos.GuardarSolicitudAgrupado');
        Route::post('/guardaEdicionProceso', [ProcesosController::class, 'guardaEdicionProceso'])->name('procesos.guardaEdicionProceso');
        Route::post('/agregarProductoAlProceso', [ProcesosController::class, 'agregarProductoAlProceso'])->name('procesos.agregarProductoAlProceso');
        Route::post('/agregarProductoProcesoNuevo', [ProcesosController::class, 'agregarProductoProcesoNuevo'])->name('procesos.agregarProductoProcesoNuevo');
        Route::post('/generaPDFHistorialProceso', [ProcesosController::class, 'generaPDFHistorialProceso'])->name('procesos.generaPDFHistorialProceso');
        Route::post('/enviarProcesoFocalizado', [ProcesosController::class, 'enviarProcesoFocalizado'])->name('procesos.enviarProcesoFocalizado');

        Route::get('/focalizadoListado', [ProcesosController::class, 'focalizadoListado'])->name('procesos.focalizadoListado');
        Route::post('/ajaxListadoSolicitudesFocalizado', [ProcesosController::class, 'ajaxListadoSolicitudesFocalizado'])->name('procesos.ajaxListadoSolicitudesFocalizado');
        Route::get('/focalizadoListadoSolicitud', [ProcesosController::class, 'focalizadoListadoSolicitud'])->name('procesos.focalizadoListadoSolicitud');
        Route::post('/guardarSolicitudFocalizado', [ProcesosController::class, 'guardarSolicitudFocalizado'])->name('procesos.guardarSolicitudFocalizado');
        Route::post('/ajaxListadoSolicitudFocalizado', [ProcesosController::class, 'ajaxListadoSolicitudFocalizado'])->name('procesos.ajaxListadoSolicitudFocalizado');
        Route::post('/ajaxlistadoPreparaciones', [ProcesosController::class, 'ajaxlistadoPreparaciones'])->name('procesos.ajaxlistadoPreparaciones');
        Route::post('/guardarNuevoProcesoPadre', [ProcesosController::class, 'guardarNuevoProcesoPadre'])->name('procesos.guardarNuevoProcesoPadre');
        Route::post('/guardarDivicacionCarga', [ProcesosController::class, 'guardarDivicacionCarga'])->name('procesos.guardarDivicacionCarga');
        Route::post('/finalizarProcesoFocalizado', [ProcesosController::class, 'finalizarProcesoFocalizado'])->name('procesos.finalizarProcesoFocalizado');

        //focalizado detalle

        Route::get('/obtener-solicitud/{id}', [ProcesosController::class, 'obtenerSolicitud']);
        Route::get('/obtener-detalle-focalizado/{id}', [ProcesosController::class, 'obtenerDetalleFocalizado']);
        Route::post('/guardar-focalizado-detalle', [ProcesosController::class, 'guardarFocalizadoDetalle'])->name('procesos.guardarFocalizadoDetalle');

        //planchado
        Route::get('/planchadoListado', [ProcesosController::class, 'planchadoListado'])->name('procesos.planchadoListado');
        Route::post('/ajaxListadoSolicitudesPlanchado', [ProcesosController::class, 'ajaxListadoSolicitudesPlanchado'])->name('procesos.ajaxListadoSolicitudesPlanchado');
        Route::post('/enviarProcesoPlanchado', [ProcesosController::class, 'enviarProcesoPlanchado'])->name('procesos.enviarProcesoPlanchado');

        Route::get('/obtener-detalle-planchado/{id}', [ProcesosController::class, 'obtenerDetallePlanchado']);
        Route::post('/guardar-planchado-detalle', [ProcesosController::class, 'guardarPlanchadoDetalle'])->name('procesos.guardarPlanchadoDetalle');
        Route::post('/finalizarProcesoPlanchado', [ProcesosController::class, 'finalizarProcesoPlanchado'])->name('procesos.finalizarProcesoPlanchado');

    });

    // REPORTE
    Route::prefix('/reporte')->group(function () {
        Route::get('/formulario', [ReporteController::class, 'formulario'])->name('reporte.formulario');
        Route::post('/cuentaPorCobrar', [ReporteController::class, 'cuentaPorCobrar'])->name('reporte.cuentaPorCobrar');
        Route::post('/cuentaPorCobrarRango', [ReporteController::class, 'cuentaPorCobrarRango'])->name('reporte.cuentaPorCobrarRango');

        Route::get('/stock', [ReporteController::class, 'formularioStock'])->name('reporte.stock.formulario');
        Route::post('/stock-historico', [ReporteController::class, 'stockHistorico'])->name('reporte.stock.historico');
        Route::post('/stock/pdf', [ReporteController::class, 'stockPdf'])->name('reporte.stock.pdf');

        Route::get('/proceso', [ReporteController::class, 'formularioProceso'])->name('reporte.proceso.formulario');
        Route::get('/proceso/ots/{factura_id}', [ReporteController::class, 'obtenerOTs'])->name('reporte.proceso.obtenerOTs');
        Route::post('/proceso/pdf', [ReporteController::class, 'procesoPdf'])->name('reporte.proceso.pdf');

        Route::get('/stockCompra', [ReporteController::class, 'formularioStockCompra'])->name('reporte.stockCompra.formulario');
        Route::post('/stock-compra/pdf', [ReporteController::class, 'reporteStockCompraPdf'])->name('reporte.stockCompra.pdf');

        Route::post('/estructuraCostos/pdf', [ReporteController::class, 'reporteEstructuraCostosPdf'])->name('reporte.estructuraCostos.pdf');
        Route::get('/costos', [ReporteController::class, 'formularioCostos'])->name('reporte.costos.formulario');

        Route::get('/prueba-ot', function () {
            $facturas = App\Models\Factura::all();
            return view('reporte.prueba_ot', compact('facturas'));
        });

        //pagos

        Route::post('/personal/lavador/pdf', [ReporteController::class, 'reporteLavador'])->name('reporte.personal.lavador.pdf');
        Route::post('/personal/auxiliar/pdf', [ReporteController::class, 'reporteAuxiliar'])->name('reporte.personal.auxiliar.pdf');
        Route::post('/personal/focalizador/pdf', [ReporteController::class, 'reporteFocalizador'])->name('reporte.personal.focalizador.pdf');
        Route::post('/personal/planchador/pdf', [ReporteController::class, 'reportePlanchador'])->name('reporte.personal.planchador.pdf');

    });



    // ORDEN DE TRABAJO
    Route::prefix('/ordenTrabajo')->group(function () {
        // Route::get('/formulario', [ReporteController::class, 'formulario'])->name('reporte.formulario');
        Route::post('/ajaxListadoOrdenTrabajos', [OrdenTrabajoController::class, 'ajaxListadoOrdenTrabajos'])->name('ordenTrabajo.ajaxListadoOrdenTrabajos');
        Route::post('/ajaxListadoOrdenTrabajosCliente', [OrdenTrabajoController::class, 'ajaxListadoOrdenTrabajosCliente'])->name('ordenTrabajo.ajaxListadoOrdenTrabajosCliente');
        Route::post('/ajaxListadoOjales', [OrdenTrabajoController::class, 'ajaxListadoOjales'])->name('ordenTrabajo.ajaxListadoOjales');
        Route::post('/ajaxListadoLaser', [OrdenTrabajoController::class, 'ajaxListadoLaser'])->name('ordenTrabajo.ajaxListadoLaser');
        Route::post('/guardarLaser', [OrdenTrabajoController::class, 'guardarLaser'])->name('ordenTrabajo.guardarLaser');
        Route::post('/ajaxFormularioEditarOrdenTrabajo', [OrdenTrabajoController::class, 'ajaxFormularioEditarOrdenTrabajo'])->name('ordenTrabajo.ajaxFormularioEditarOrdenTrabajo');
        Route::post('/ajaxNroOtFactura', [OrdenTrabajoController::class, 'ajaxNroOtFactura'])->name('ordenTrabajo.ajaxNroOtFactura');
        Route::post('/guardarEstadoOrdenTrabajo', [OrdenTrabajoController::class, 'guardarEstadoOrdenTrabajo'])->name('ordenTrabajo.guardarEstadoOrdenTrabajo');
        Route::post('/ajaxFormularioLaser', [OrdenTrabajoController::class, 'ajaxFormularioLaser'])->name('ordenTrabajo.ajaxFormularioLaser');
        Route::get('/imprimirOrdenTrabajo/{factura_id}/{nro_orden}', [OrdenTrabajoController::class, 'imprimirOrdenTrabajo'])->name('ordenTrabajo.imprimirOrdenTrabajo');

        Route::get('/rol', [OrdenTrabajoController::class, 'vistaRol'])->name('order-trabajo.rol');

        // Guardar cantidad de planchado/focalizado
        Route::post('/guardar-cantidad', [OrdenTrabajoController::class, 'guardarCantidad'])->name('order-trabajo.guardarCantidad');

        // Obtener OTs por factura (AJAX)
        Route::get('/ots/{factura_id}', [OrdenTrabajoController::class, 'obtenerOTs'])->name('order-trabajo.obtenerOTs');
        Route::get('/listadoCantidades', [OrdenTrabajoController::class, 'listadoCantidades'])->name('order-trabajo.listadoCantidades');
        Route::get('/listarCantidades', [OrdenTrabajoController::class, 'listarCantidades'])->name('order-trabajo.listarCantidades');
        Route::post('/cambiaDatoOrdenTrabajo', [OrdenTrabajoController::class, 'cambiaDatoOrdenTrabajo'])->name('ordenTrabajo.cambiaDatoOrdenTrabajo');
    });



    Route::prefix('/facturaCliente')->group(function () {
        Route::get('/listadoFacturaCliente', [FacturaController::class, 'listadoFacturaCliente'])->name('factura.listadoFacturaCliente');
        Route::post('/ajaxListadoFacturasCliente', [FacturaController::class, 'ajaxListadoFacturasCliente'])->name('factura.ajaxListadoFacturasCliente');
        Route::get('/detalleCliente/{factura_id}', [FacturaController::class, 'detalleCliente'])->name('factura.detalleCliente');
    });

    //solicitudes
    Route::prefix('/solicitudes')->group(function () {
        Route::get('/listado', [SolicitudController::class, 'listado'])->name('solicitudes.listado');
        Route::post('/ajaxListado', [SolicitudController::class, 'ajaxListado'])->name('solicitudes.ajaxListado');
        Route::post('/store', [SolicitudController::class, 'store'])->name('solicitudes.store');
        Route::post('/ajaxDetalleOT', [SolicitudController::class, 'ajaxDetalleOT'])->name('solicitudes.ajaxDetalleOT');
        Route::post('/accionProducto', [SolicitudController::class, 'accionProducto'])->name('solicitudes.accionProducto');
        Route::get('/ots', [SolicitudController::class, 'listaOTs'])->name('solicitudes.listaOTs');
        // Route::get('/ots-por-factura/{factura_id}', [SolicitudController::class, 'otsPorFactura'])->name('solicitudes.otsPorFactura');
        Route::get('/ots/{id}', [SolicitudController::class, 'otsPorFactura'])->name('solicitudes.otsPorFactura');

        Route::get('/codigos-compra', [SolicitudController::class, 'codigosCompra'])->name('solicitudes.codigosCompra');
        Route::get('/productos-con-stock', [SolicitudController::class, 'productosConStock'])->name('solicitudes.productosConStock');

        Route::get('/productos-stock', [SolicitudController::class, 'productosConStock'])->name('solicitudes.productosConStock');
        Route::get('/detalle/{id}', [SolicitudController::class, 'detalle'])->name('solicitudes.detalle');
        Route::post('/ajax-detalle-ot', [SolicitudController::class, 'ajaxDetalleOT'])->name('solicitudes.ajaxDetalleOT');
        Route::post('/verDetalleSolicitud', [SolicitudController::class, 'verDetalleSolicitud'])->name('solicitudes.verDetalleSolicitud');

        Route::post('/ajaxProductoSolicitud', [SolicitudController::class, 'ajaxProductoSolicitud'])->name('solicitudes.ajaxProductoSolicitud');
        Route::post('/buscarSolicitudesProducto', [SolicitudController::class, 'buscarSolicitudesProducto'])->name('solicitudes.buscarSolicitudesProducto');

        Route::post('/focalizado', [SolicitudController::class, 'storeFocalizado'])->name('solicitudes.store.focalizado');

    });

    // NEVADO
    Route::prefix('/nevado')->group(function () {
        Route::get('/listado', [NevadoController::class, 'listado'])->name('nevado.listado');
        Route::post('/ajaxListado', [NevadoController::class, 'ajaxListado'])->name('nevado.ajaxListado');
        Route::post('/guardarNevado', [NevadoController::class, 'guardarNevado'])->name('nevado.guardarNevado');
        Route::post('/eliminarNevado', [NevadoController::class, 'eliminarNevado'])->name('nevado.eliminarNevado');
    });

    //PERSONAL

    Route::prefix('control-personal')->group(function () {

        Route::get('/', [ControlPersonalController::class, 'index'])->name('personal.index');
        Route::get('/listado', [ControlPersonalController::class, 'ajaxListado'])->name('personal.ajaxListado');
        // Route::get('/resumen/{user}', [PagosPersonalController::class, 'ajaxResumen'])->name('personal.resumen');
        Route::post('/configuracion/update', [ConfiguracionPersonalController::class, 'update'])->name('personal.config.update');

        Route::get('/user/{id}', [ControlPersonalController::class, 'getUser']);
        Route::get('/resumen-fechas/{user}', [ControlPersonalController::class, 'resumenFechas']);
        Route::post('/pagos-personal/store', [ControlPersonalController::class, 'store']);

        Route::get('/lavador/formulario', [ControlPersonalController::class, 'formularioLavador']);
        Route::get('/auxiliar/formulario', [ControlPersonalController::class, 'formularioAuxiliar']);
        Route::get('/focalizador/formulario', [ControlPersonalController::class, 'formularioFocalizador']);
        Route::get('/planchador/formulario', [ControlPersonalController::class, 'formularioPlanchador']);

        Route::get('/{user}', [ControlPersonalController::class, 'show'])->name('personal.show');

    });

    Route::prefix('asistencias')->group(function () {
        Route::get('/listado/{user}', [AsistenciaController::class, 'ajaxListado'])->name('asistencias.listado');
        Route::post('/store', [AsistenciaController::class, 'store'])->name('asistencias.store');
        Route::post('/delete', [AsistenciaController::class, 'delete'])->name('asistencias.delete');
        Route::get('/list/{user_id}', [AsistenciaController::class, 'listar']);
    });

    Route::prefix('pagos-personal')->group(function () {
        Route::post('/store', [PagosPersonalController::class, 'store'])->name('pagos.store');
        Route::get('/historial/{user}', [PagosPersonalController::class, 'historial'])->name('pagos.historial');
    });

    //pago focalizado plancahdo
    Route::prefix('produccion-pago')->group(function () {
        Route::post('/resumen', [ProduccionPagoController::class, 'resumen']);
        Route::post('/pagar', [ProduccionPagoController::class, 'pagar']);

    });


    Route::prefix('/entregas')->group(function () {

        Route::get('/listado', [ProcesosController::class, 'listadoEntregado'])->name('entregas.listado');
        Route::post('/ajaxListadoEntregado', [ProcesosController::class, 'ajaxListadoEntregado'])->name('entregas.ajaxListado');
        Route::post('/confirmar-entrega', [ProcesosController::class, 'confirmarEntrega'])->name('entregas.confirmarEntrega');

    });


    //deudas
    Route::prefix('/deudas')->group(function () {
        Route::get('/{user_id}', [DeudaController::class, 'listarPorUsuario']);
        Route::post('/store', [DeudaController::class, 'store']);
        Route::post('/descontar', [DeudaController::class, 'descontar']);
        Route::get('/movimientos/{deuda_id}', [DeudaController::class, 'movimientos']);
        Route::get('/reporte/{id}', [DeudaController::class, 'reporte']);
    });

});
