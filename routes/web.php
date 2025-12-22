<?php

use App\Http\Controllers\CaracteristicaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ColorTelaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\FocalizadoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\NombreTelaController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\PrelavadoController;
use App\Http\Controllers\PrendaController;
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
    // USUARIO
    Route::prefix('/user')->group(function () {
        Route::get('/listado', [UserController::class, 'listado'])->name('user.listado');
        Route::post('/ajaxListado', [UserController::class, 'ajaxListado'])->name('user.ajaxListado');
        Route::post('/guardarUser', [UserController::class, 'guardarUser'])->name('user.guardarUser');
        Route::post('/eliminarUser', [UserController::class, 'eliminarUser'])->name('user.eliminarUser');
    });
    // CLIENTE
    Route::prefix('/cliente')->group(function () {
        Route::get('/listado', [ClienteController::class, 'listado'])->name('cliente.listado');
        Route::post('/ajaxListado', [ClienteController::class, 'ajaxListado'])->name('cliente.ajaxListado');
        Route::post('/guardarCliente', [ClienteController::class, 'guardarCliente'])->name('cliente.guardarCliente');
        Route::post('/eliminarCliente', [ClienteController::class, 'eliminarCliente'])->name('cliente.eliminarCliente');
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
        Route::get('/comprobantePago/{pago_id}', [PagoController::class, 'comprobantePago'])->name('pago.comprobantePago');
    });

    //PROCESOS
    Route::prefix('/procesos')->group(function () {

        Route::get('/listado', [ProcesosController::class, 'listado'])->name('procesos.listado');
        Route::post('/ajaxListado', [ProcesosController::class, 'ajaxListado'])->name('procesos.ajaxListado');

        Route::get('/lista-productos', [ProcesosController::class, 'listaProductos'])
            ->name('procesos.listaProductos');

        Route::get('/lista-tipos-proceso', [ProcesosController::class, 'listaTiposProceso'])
            ->name('procesos.listaTiposProceso');
        Route::post('/guardar', [ProcesosController::class, 'guardar'])->name('procesos.guardar');
        // web.php
        Route::post('/info-maquinaria', [ProcesosController::class, 'infoMaquinaria'])->name('procesos.infoMaquinaria');
        Route::post('/actualizar-estados', [ProcesosController::class, 'actualizarEstados'])->name('procesos.actualizarEstados');

        Route::get('/lista-ots', [ProcesosController::class, 'listaOTs'])->name('procesos.listaOTs');
        Route::get('/detalle-ot/{ot}', [ProcesosController::class, 'detalleOT']);

        Route::post('/finalizar-ot', [ProcesosController::class, 'finalizarOT'])->name('procesos.finalizarOT');
        Route::get('/productos-aceptados', [ProcesosController::class, 'productosSolicitudesAceptadas'])
            ->name('procesos.productosSolicitudesAceptadas');

        Route::get('/productos-movimientos', [ProcesosController::class, 'productosMovimientos'])
            ->name('procesos.productosMovimientos');
        Route::get('/productos-aceptados', [ProcesosController::class, 'productosSolicitudesAceptadas'])->name('procesos.productosSolicitudesAceptadas');

    });

    // REPORTE
    Route::prefix('/reporte')->group(function () {
        Route::get('/formulario', [ReporteController::class, 'formulario'])->name('reporte.formulario');
        Route::post('/cuentaPorCobrar', [ReporteController::class, 'cuentaPorCobrar'])->name('reporte.cuentaPorCobrar');
    });

    // ORDEN DE TRABAJO
    Route::prefix('/ordenTrabajo')->group(function () {
        // Route::get('/formulario', [ReporteController::class, 'formulario'])->name('reporte.formulario');
        Route::post('/ajaxListadoOrdenTrabajos', [OrdenTrabajoController::class, 'ajaxListadoOrdenTrabajos'])->name('ordenTrabajo.ajaxListadoOrdenTrabajos');
        Route::post('/ajaxListadoOjales', [OrdenTrabajoController::class, 'ajaxListadoOjales'])->name('ordenTrabajo.ajaxListadoOjales');
        Route::post('/ajaxListadoLaser', [OrdenTrabajoController::class, 'ajaxListadoLaser'])->name('ordenTrabajo.ajaxListadoLaser');
        Route::post('/guardarLaser', [OrdenTrabajoController::class, 'guardarLaser'])->name('ordenTrabajo.guardarLaser');
    });

    //solicitudes
    Route::prefix('/solicitudes')->group(function () {
        Route::get('/listado', [SolicitudController::class, 'listado'])->name('solicitudes.listado');
        Route::post('/ajaxListado', [SolicitudController::class, 'ajaxListado'])->name('solicitudes.ajaxListado');
        Route::post('/solicitudes/store', [SolicitudController::class, 'store'])->name('solicitudes.store');
        Route::post('/ajaxDetalleOT', [SolicitudController::class, 'ajaxDetalleOT'])->name('solicitudes.ajaxDetalleOT');
        Route::post('/accionProducto', [SolicitudController::class, 'accionProducto'])->name('solicitudes.accionProducto');

    });



});
