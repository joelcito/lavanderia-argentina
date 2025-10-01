<?php

use App\Http\Controllers\CaracteristicaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrendaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\SucursalController;
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
});
