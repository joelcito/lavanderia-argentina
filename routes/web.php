<?php

use App\Http\Controllers\CaracteristicaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ColorTelaController;
use App\Http\Controllers\FocalizadoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MaquinariaController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\NombreTelaController;
use App\Http\Controllers\PrelavadoController;
use App\Http\Controllers\PrendaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\TipoProcesoController;
use App\Http\Controllers\TipoTelaController;
use App\Http\Controllers\UserController;
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
    Route::prefix('/rol')->group(function(){
        Route::get('/listado', [RolController::class, 'listado'])->name('rol.listado');
        Route::post('/ajaxListado', [RolController::class, 'ajaxListado'])->name('rol.ajaxListado');
        Route::post('/guardarRol', [RolController::class, 'guardarRol'])->name('rol.guardarRol');
        Route::post('/eliminarRol', [RolController::class, 'eliminarRol'])->name('rol.eliminarRol');
    });
     // USUARIO
    Route::prefix('/user')->group(function(){
        Route::get('/listado', [UserController::class, 'listado'])->name('user.listado');
        Route::post('/ajaxListado', [UserController::class, 'ajaxListado'])->name('user.ajaxListado');
        Route::post('/guardarUser', [UserController::class, 'guardarUser'])->name('user.guardarUser');
        Route::post('/eliminarUser', [UserController::class, 'eliminarUser'])->name('user.eliminarUser');    
    });
     // CLIENTE
    Route::prefix('/cliente')->group(function(){
        Route::get('/listado', [ClienteController::class, 'listado'])->name('cliente.listado');
        Route::post('/ajaxListado', [ClienteController::class, 'ajaxListado'])->name('cliente.ajaxListado');
        Route::post('/guardarCliente', [ClienteController::class, 'guardarCliente'])->name('cliente.guardarCliente');
        Route::post('/eliminarCliente', [ClienteController::class, 'eliminarCliente'])->name('cliente.eliminarCliente');    
    });
    // PRENDA
    Route::prefix('/prenda')->group(function(){
        Route::get('/listado', [PrendaController::class, 'listado'])->name('prenda.listado');
        Route::post('/ajaxListado', [PrendaController::class, 'ajaxListado'])->name('prenda.ajaxListado');
        Route::post('/guardarPrenda', [PrendaController::class, 'guardarPrenda'])->name('prenda.guardarPrenda');
        Route::post('/eliminarPrenda', [PrendaController::class, 'eliminarPrenda'])->name('prenda.eliminarPrenda');
    });
    // TIPO TELA
    Route::prefix('/tipo_tela')->group(function(){
        Route::get('/listado', [TipoTelaController::class, 'listado'])->name('tipo_tela.listado');
        Route::post('/ajaxListado', [TipoTelaController::class, 'ajaxListado'])->name('tipo_tela.ajaxListado');
        Route::post('/guardarTipoTela', [TipoTelaController::class, 'guardarTipoTela'])->name('tipo_tela.guardarTipoTela');
        Route::post('/eliminarTipoTela', [TipoTelaController::class, 'eliminarTipoTela'])->name('tipo_tela.eliminarTipoTela');
    });
    // CARACTERISTICA
    Route::prefix('/caracteristica')->group(function(){
        Route::get('/listado', [CaracteristicaController::class, 'listado'])->name('caracteristica.listado');
        Route::post('/ajaxListado', [CaracteristicaController::class, 'ajaxListado'])->name('caracteristica.ajaxListado');
        Route::post('/guardarCaracteristica', [CaracteristicaController::class, 'guardarCaracteristica'])->name('caracteristica.guardarCaracteristica');
        Route::post('/eliminarCaracteristica', [CaracteristicaController::class, 'eliminarCaracteristica'])->name('caracteristica.eliminarCaracteristica');
    });
    // SUCURSAL
    Route::prefix('/sucursal')->group(function(){
        Route::get('/listado', [SucursalController::class, 'listado'])->name('sucursal.listado');
        Route::post('/ajaxListado', [SucursalController::class, 'ajaxListado'])->name('sucursal.ajaxListado');
        Route::post('/guardarSucursal', [SucursalController::class, 'guardarSucursal'])->name('sucursal.guardarSucursal');
        Route::post('/eliminarSucursal', [SucursalController::class, 'eliminarSucursal'])->name('sucursal.eliminarSucursal');
    });
    // PROVEEDOR
    Route::prefix('/proveedor')->group(function(){
        Route::get('/listado', [ProveedorController::class, 'listado'])->name('proveedor.listado');
        Route::post('/ajaxListado', [ProveedorController::class, 'ajaxListado'])->name('proveedor.ajaxListado');
        Route::post('/guardarProveedor', [ProveedorController::class, 'guardarProveedor'])->name('proveedor.guardarProveedor');
        Route::post('/eliminarProveedor', [ProveedorController::class, 'eliminarProveedor'])->name('proveedor.eliminarProveedor');
    });
    // PRODUCTO
    Route::prefix('/producto')->group(function(){
        Route::get('/listado', [ProductoController::class, 'listado'])->name('producto.listado');
        Route::post('/ajaxListado', [ProductoController::class, 'ajaxListado'])->name('producto.ajaxListado');
        Route::post('/guardarProducto', [ProductoController::class, 'guardarProducto'])->name('producto.guardarProducto');
        Route::post('/eliminarProducto', [ProductoController::class, 'eliminarProducto'])->name('producto.eliminarProducto');
    });
    // MAQUINARIAS
    Route::prefix('/maquinaria')->group(function(){
        Route::get('/listado', [MaquinariaController::class, 'listado'])->name('maquinaria.listado');
        Route::post('/ajaxListado', [MaquinariaController::class, 'ajaxListado'])->name('maquinaria.ajaxListado');
        Route::post('/guardarMaquinaria', [MaquinariaController::class, 'guardarMaquinaria'])->name('maquinaria.guardarMaquinaria');
        Route::post('/eliminarMaquinaria', [MaquinariaController::class, 'eliminarMaquinaria'])->name('maquinaria.eliminarMaquinaria');
    });
    // color TELA
    Route::prefix('/color_tela')->group(function(){
        Route::get('/listado', [ColorTelaController::class, 'listado'])->name('color_tela.listado');
        Route::post('/ajaxListado', [ColorTelaController::class, 'ajaxListado'])->name('color_tela.ajaxListado');
        Route::post('/guardarColorTela', [ColorTelaController::class, 'guardarColorTela'])->name('color_tela.guardarColorTela');
        Route::post('/eliminarColorTela', [ColorTelaController::class, 'eliminarColorTela'])->name('color_tela.eliminarColorTela');
    });
    // NOMBRE TELA
    Route::prefix('/nombre_tela')->group(function(){
        Route::get('/listado', [NombreTelaController::class, 'listado'])->name('nombre_tela.listado');
        Route::post('/ajaxListado', [NombreTelaController::class, 'ajaxListado'])->name('nombre_tela.ajaxListado');
        Route::post('/guardarNombreTela', [NombreTelaController::class, 'guardarNombreTela'])->name('nombre_tela.guardarNombreTela');
        Route::post('/eliminarNombreTela', [NombreTelaController::class, 'eliminarNombreTela'])->name('nombre_tela.eliminarNombreTela');
    });
    // TIPO PROCESO
    Route::prefix('/tipo_proceso')->group(function(){
        Route::get('/listado', [TipoProcesoController::class, 'listado'])->name('tipo_proceso.listado');
        Route::post('/ajaxListado', [TipoProcesoController::class, 'ajaxListado'])->name('tipo_proceso.ajaxListado');
        Route::post('/guardarTipoProceso', [TipoProcesoController::class, 'guardarTipoProceso'])->name('tipo_proceso.guardarTipoProceso');
        Route::post('/eliminarTipoProceso', [TipoProcesoController::class, 'eliminarTipoProceso'])->name('tipo_proceso.eliminarTipoProceso');
    });
    // PRELAVADO
    Route::prefix('/prelavado')->group(function(){
        Route::get('/listado', [PrelavadoController::class, 'listado'])->name('prelavado.listado');
        Route::post('/ajaxListado', [PrelavadoController::class, 'ajaxListado'])->name('prelavado.ajaxListado');
        Route::post('/guardarPrelavado', [PrelavadoController::class, 'guardarPrelavado'])->name('prelavado.guardarPrelavado');
        Route::post('/eliminarPrelavado', [PrelavadoController::class, 'eliminarPrelavado'])->name('prelavado.eliminarPrelavado');
    });
    // FOCALIZADO
    Route::prefix('/focalizado')->group(function(){
        Route::get('/listado', [FocalizadoController::class, 'listado'])->name('focalizado.listado');
        Route::post('/ajaxListado', [FocalizadoController::class, 'ajaxListado'])->name('focalizado.ajaxListado');
        Route::post('/guardarFocalizado', [FocalizadoController::class, 'guardarFocalizado'])->name('focalizado.guardarFocalizado');
        Route::post('/eliminarFocalizado', [FocalizadoController::class, 'eliminarFocalizado'])->name('focalizado.eliminarFocalizado');
    });
    // MOVIMIENTO
    Route::prefix('/movimiento')->group(function(){
        Route::get('movimiento/{producto}/stock', [MovimientoController::class, 'listarStock'])->name('movimiento.listarStock');
        Route::post('movimiento/ingreso', [MovimientoController::class, 'agregarIngreso'])->name('movimiento.agregarIngreso');
        Route::post('movimiento/egreso', [MovimientoController::class, 'agregarEgreso'])->name('movimiento.agregarEgreso');
        //Route::get('/listado', [MovimientoController::class, 'listado'])->name('movimiento.listado');
        Route::post('/ajaxListado', [MovimientoController::class, 'ajaxListado'])->name('movimiento.ajaxListado');
        //Route::post('/guardarMovimiento', [MovimientoController::class, 'guardarMovimiento'])->name('movimiento.guardarMovimiento');
    });
});
