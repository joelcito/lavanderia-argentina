<?php

namespace Database\Seeders;

use App\Models\Permiso;
use Illuminate\Database\Seeder;

class PermisoSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | MÓDULOS
        |--------------------------------------------------------------------------
        |
        | Todos estos módulos tendrán:
        |
        | ver
        | crear
        | editar
        | eliminar
        |
        */

        $grupos = [

            'Administración' => [
                'Usuarios'               => 'usuarios',
                'Roles'                  => 'roles',
                'Clientes'               => 'clientes',
                'Proveedores'            => 'proveedores',
                'Categorías'             => 'categorias',
                'Sub Categorías'         => 'subcategorias',
                'Prendas'                => 'prendas',
                'Tipos de Telas'         => 'tipos_tela',
                'Colores de Telas'       => 'colores_tela',
                'Nombres de Telas'       => 'nombres_tela',
                'Tipos de Proceso'       => 'tipos_proceso',
                'Prelavados'             => 'prelavados',
                'Focalizados'            => 'focalizados',
                'Características'        => 'caracteristicas',
                'Sucursales'             => 'sucursales',
                'Maquinarias'            => 'maquinarias',
                'Productos'              => 'productos',
                'Cuentas por Cobrar'     => 'cuentas_cobrar',
                'Solicitudes'            => 'solicitudes',
                'Nevados'                => 'nevados',
                'Planchador/Focalizador' => 'personal_roles',
                'Control de Personal'    => 'personal',
                'Cotizaciones'           => 'cotizaciones',
                'Recetas'                => 'recetas',
            ],

            'Ventas' => [

                'Recepción'            => 'recepcion',
                'Listado Venta'        => 'ventas',
                'Ventas del Día'       => 'ventas_dia',
                'Procesos'             => 'procesos',
                'Entregas'             => 'entregas',
            ],

            'Reportes' => [

                'Cuentas Cliente'      => 'reportes_cuentas_cliente',
                'Stock Histórico'      => 'reportes_stock_historico',
                'Estructura de Costos' => 'reportes_estructura_costos',
            ],

            'Focalizado' => [

                'Focalizado'           => 'focalizado',
            ],

            'Planchado' => [

                'Planchado'            => 'planchado',
            ],

            'Seguimiento' => [

                'Notas Recepción'      => 'seguimiento_notas_recepcion',
            ],
        ];


        /*
        |--------------------------------------------------------------------------
        | ACCIONES GENERALES
        |--------------------------------------------------------------------------
        */

        $acciones = [

            'ver' => 'Ver',

            'crear' => 'Crear',

            'editar' => 'Editar',

            'eliminar' => 'Eliminar',

        ];


        /*
        |--------------------------------------------------------------------------
        | GENERAMOS LOS PERMISOS
        |--------------------------------------------------------------------------
        */

        foreach ($grupos as $grupo => $modulos) {

            foreach ($modulos as $nombreModulo => $codigoModulo) {

                foreach ($acciones as $accion => $nombreAccion) {

                    /*
                     * Algunos módulos tienen códigos especiales
                     * para mantener compatibilidad con lo que ya hicimos.
                     */

                    $codigo = match ($codigoModulo) {

                        'reportes_cuentas_cliente' =>
                        "reportes.cuentas_cliente.{$accion}",

                        'reportes_stock_historico' =>
                        "reportes.stock_historico.{$accion}",

                        'reportes_estructura_costos' =>
                        "reportes.estructura_costos.{$accion}",

                        'seguimiento_notas_recepcion' =>
                        "seguimiento.notas_recepcion.{$accion}",

                        default =>
                        "{$codigoModulo}.{$accion}",
                    };


                    Permiso::updateOrCreate(

                        [
                            'codigo' => $codigo,
                        ],

                        [
                            'grupo' => $grupo,

                            'modulo' => $nombreModulo,

                            'nombre' => "{$nombreAccion} {$nombreModulo}",

                            'accion' => $accion,

                            'estado' => 'ACTIVO',
                        ]

                    );
                }
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PERMISOS ESPECIALES
        |--------------------------------------------------------------------------
        */

        $especiales = [

            // [
            //     'grupo' => 'Administración',
            //     'modulo' => 'Roles',
            //     'nombre' => 'Administrar permisos',
            //     'codigo' => 'roles.permisos',
            //     'accion' => 'permisos',
            // ],

            [
                'grupo' => 'Administración',
                'modulo' => 'Solicitudes',
                'nombre' => 'Aprobar solicitudes',
                'codigo' => 'solicitudes.aprobar',
                'accion' => 'aprobar',
            ],

            [
                'grupo' => 'Focalizado',
                'modulo' => 'Focalizado',
                'nombre' => 'Ver solicitudes para focalizar',
                'codigo' => 'focalizado.solicitudes',
                'accion' => 'solicitudes',
            ],

            [
                'grupo' => 'Administración',
                'modulo' => 'Clientes',
                'nombre' => 'Ver perfil Cliente',
                'codigo' => 'clientes.perfil',
                'accion' => 'perfil',
            ],
        ];


        foreach ($especiales as $permiso) {

            Permiso::updateOrCreate(

                [
                    'codigo' => $permiso['codigo'],
                ],

                [
                    'grupo' => $permiso['grupo'],

                    'modulo' => $permiso['modulo'],

                    'nombre' => $permiso['nombre'],

                    'accion' => $permiso['accion'],

                    'estado' => 'ACTIVO',
                ]

            );
        }
    }
}
