<?php

namespace Database\Seeders;

use App\Models\Permiso;
use App\Models\Rol;
use Illuminate\Database\Seeder;

class RolPermisoSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ROL 1 - ADMINISTRADOR DE SISTEMA
        |--------------------------------------------------------------------------
        |
        | El administrador tendrá TODOS los permisos.
        |
        */

        $administrador = Rol::find(1);

        if ($administrador) {
            $administrador->permisos()->sync(
                Permiso::pluck('id')->toArray()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PERMISOS DEL BLOQUE GENERAL ANTIGUO
        |--------------------------------------------------------------------------
        |
        | En tu menú anterior estos Rols podían ingresar al bloque:
        |
        | - LAVADOR
        | - ENCARGADO DE ALMACEN INSUMOS
        | - AYUDANTE DE LAVADOR 1
        | - AUXILIAR DE OFICINA 1
        |
        | Por seguridad migramos inicialmente los accesos VISIBLES que ya tenían.
        |
        */

        $permisosGenerales = [

            // ADMINISTRACIÓN

            'usuarios.ver',
            'Rols.ver',
            'clientes.ver',
            'proveedores.ver',
            'categorias.ver',
            'subcategorias.ver',
            'prendas.ver',
            'tipos_tela.ver',
            'colores_tela.ver',
            'nombres_tela.ver',
            'tipos_proceso.ver',
            'prelavados.ver',
            'focalizados.ver',
            'caracteristicas.ver',
            'sucursales.ver',
            'maquinarias.ver',
            'productos.ver',
            'cuentas_cobrar.ver',
            'solicitudes.ver',
            'nevados.ver',
            'personal_Rols.ver',
            'personal.ver',
            'cotizaciones.ver',
            'recetas.ver',

            // VENTAS

            'recepcion.ver',
            'ventas.ver',
            'ventas_dia.ver',
            'procesos.ver',
            'entregas.ver',

            // REPORTES

            'reportes.cuentas_cliente',
            'reportes.stock_historico',
            'reportes.estructura_costos',
        ];

        $idsPermisosGenerales = Permiso::whereIn(
            'codigo',
            $permisosGenerales
        )->pluck('id')->toArray();


        /*
        |--------------------------------------------------------------------------
        | ROL 2 - LAVADOR
        |--------------------------------------------------------------------------
        */

        $lavador = Rol::find(2);

        if ($lavador) {
            $lavador->permisos()->sync(
                $idsPermisosGenerales
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ROL 3 - CLIENTE
        |--------------------------------------------------------------------------
        */

        $cliente = Rol::find(3);

        if ($cliente) {

            $cliente->permisos()->sync(
                Permiso::whereIn('codigo', [
                    'seguimiento.notas_recepcion',
                ])->pluck('id')->toArray()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ROL 4 - ENCARGADO DE ALMACEN INSUMOS
        |--------------------------------------------------------------------------
        */

        $almacen = Rol::find(4);

        if ($almacen) {
            $almacen->permisos()->sync(
                $idsPermisosGenerales
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ROL 5 - PLANCHADOR 1
        |--------------------------------------------------------------------------
        */

        $planchador = Rol::find(5);

        if ($planchador) {

            $planchador->permisos()->sync(
                Permiso::whereIn('codigo', [
                    'planchado.lista',
                ])->pluck('id')->toArray()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ROL 6 - FOCALIZADOR 1
        |--------------------------------------------------------------------------
        */

        $focalizador = Rol::find(6);

        if ($focalizador) {

            $focalizador->permisos()->sync(
                Permiso::whereIn('codigo', [
                    'focalizado.lista',
                    'focalizado.solicitudes',
                ])->pluck('id')->toArray()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ROL 7 - AYUDANTE DE LAVADOR 1
        |--------------------------------------------------------------------------
        */

        $ayudanteLavador = Rol::find(7);

        if ($ayudanteLavador) {
            $ayudanteLavador->permisos()->sync(
                $idsPermisosGenerales
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ROL 8 - AUXILIAR DE OFICINA 1
        |--------------------------------------------------------------------------
        */

        $auxiliarOficina = Rol::find(8);

        if ($auxiliarOficina) {
            $auxiliarOficina->permisos()->sync(
                $idsPermisosGenerales
            );
        }
    }
}
