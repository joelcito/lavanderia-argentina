<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Rol;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolController extends Controller
{
    public function listado(){
        return view ('rol.listado');
    }

    public function ajaxListado(Request $request){

        if($request->ajax()){

            //SACAMOS EL LISTADO
            $roles = Rol::all();

            $valores = [
                'listado' => view('rol.ajaxListado')->with(compact('roles'))->render()
            ];

            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }
        return $data;
    }

    public function guardarRol(Request $request){

        if($request->ajax()){

            //AL INICIO DECLARACION DE VARIABLES
            $rol_id = $request->input('id');
            $nombre = $request->input('nombre');
            $usuario = Auth::user();

            if($rol_id == '0'){
                //LA CREACION DE UN NUEVO ROL
                $rol = new Rol();
                $rol->usuario_creador_id = $usuario->id;

            }else{
                //LA EDICION DE UN NUEVO ROL
                $rol = Rol::find($rol_id);
                $rol->usuario_modificador_id = $usuario->id;
            }

            $rol->nombre = $nombre;
            $rol->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");

        }else{
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }

    public function eliminarRol(Request $request){

        if($request->ajax()){

            //INICIALIZAMOS LAS VARIABLES
            $rol_id = $request->input('rol');
            $usuario = Auth::user();

            //BUSCAMOS AL ROL
            $rol = Rol::find($rol_id);
            $rol->usuario_eliminador_id = $usuario->id;
            $rol->save();

            //AHORA ELIMINAMOS
            Rol::destroy($rol_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        }else{

            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }

    public function obtenerPermisos(Request $request)
    {
        $request->validate([
            'rol_id' => 'required|exists:roles,id',
        ]);

        $rol = Rol::with('permisos')
            ->findOrFail($request->rol_id);

        $permisosAsignados = $rol->permisos
            ->pluck('id')
            ->toArray();


        $permisos = Permiso::where('estado', 'ACTIVO')
            ->orderBy('grupo')
            ->orderBy('modulo')
            ->orderBy('id')
            ->get();


        $resultado = $permisos
            ->groupBy('grupo')
            ->map(function ($permisosGrupo, $grupo) use ($permisosAsignados) {

                /*
             * Código CSS/JS seguro.
             * Ejemplo:
             *
             * Administración
             * administracion
             */

                $codigoGrupo = \Illuminate\Support\Str::slug(
                    $grupo,
                    '_'
                );


                $modulos = $permisosGrupo
                    ->groupBy('modulo')
                    ->map(function ($permisosModulo, $modulo) use ($permisosAsignados) {

                        return [

                            'modulo' => $modulo,

                            'permisos' => $permisosModulo
                                ->map(function ($permiso) use ($permisosAsignados) {

                                    return [
                                        'id'       => $permiso->id,
                                        'nombre'   => $permiso->nombre,
                                        'codigo'   => $permiso->codigo,
                                        'accion'   => ucfirst($permiso->accion),

                                        'asignado' => in_array(
                                            $permiso->id,
                                            $permisosAsignados
                                        ),
                                    ];
                                })
                                ->values(),

                        ];
                    })
                    ->values();

                return [

                    'grupo' => $grupo,

                    'codigo_grupo' => $codigoGrupo,

                    'modulos' => $modulos,

                ];
            })
            ->values();


        return response()->json([
            'estado'   => true,
            'permisos' => $resultado,
        ]);
    }

    public function guardarPermisos(Request $request)
    {
        $request->validate([
            'rol_id'      => 'required|exists:roles,id',
            'permisos'    => 'nullable|array',
            'permisos.*'  => 'exists:permisos,id',
        ]);


        $rol = Rol::findOrFail(
            $request->rol_id
        );


        $rol->permisos()->sync(
            $request->permisos ?? []
        );


        return response()->json([
            'estado'  => true,
            'message' => 'Permisos actualizados correctamente.',
        ]);
    }
}
