<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Rol;
use App\Models\Sucursal;
use App\Models\User;
use App\Utils\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function listado()
    {

        // $roles = Rol::all();
        $roles = Rol::where('id', '!=', 3)->get();
        $sucursales = Sucursal::all();

        return view('user.listado')->with(compact('roles', 'sucursales'));
    }

    public function ajaxListado(Request $request)
    {
        if ($request->ajax()) {
            //sacamos el listado
            // $usuarios = User::all();
            $usuarios = User::where('rol_id', '!=', 3)->get();
            $valores = [
                'listado' => view('user.ajaxListado')->with(compact('usuarios'))->render()
            ];
            $data = Respuesta::success($valores, "Datos Obtenidos correctamente");
        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");

        }
        return $data;
    }

    public function guardarUser(Request $request)
    {
        if ($request->ajax()) {
            $user_id = $request->input('id');
            $sucursal_id = $request->input('sucursal_id');
            $rol_id = $request->input('rol_id');
            $nombre = $request->input('nombre');
            $ap_paterno = $request->input('ap_paterno');
            $ap_materno = $request->input('ap_materno');
            $cedula = $request->input('cedula');
            $celular = $request->input('celular');
            $name = $request->input('name');
            $email = $request->input('email');
            $password = Hash::make($request->input('password'));
            $usuario = Auth::user();

            if ($user_id == '0') {
                $user = new User();
                $user->usuario_creador_id = $usuario->id;
            } else {
                $user = User::find($user_id);
                $user->usuario_modificador_id = $usuario->id;
            }

            $user->rol_id = $rol_id;
            $user->sucursal_id = $sucursal_id;
            $user->nombres = $nombre;
            $user->ap_paterno = $ap_paterno;
            $user->ap_materno = $ap_materno;
            $user->cedula = $cedula;
            $user->celular = $celular;
            $user->name = $name;
            $user->email = $email;
            $user->password = $password;
            $user->save();

            $data = Respuesta::success(null, "Datos Obtenidos correctamente");
        } else {
            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;
    }

    public function eliminarUser(Request $request)
    {

        if ($request->ajax()) {

            //INICIALIZAMOS LAS VARIABLES
            $user_id = $request->input('user');
            $usuario = Auth::user();

            //BUSCAMOS AL USER
            $user = User::find($user_id);
            $user->usuario_eliminador_id = $usuario->id;
            $user->save();

            //AHORA ELIMINAMOS
            User::destroy($user_id);

            $data = Respuesta::success(null, "Se elimino con exito");

        } else {

            $data = Respuesta::error(null, "Error al obtener los datos");
        }

        return $data;

    }


    public function getUser($id)
    {
        return response()->json(
            User::select('id', 'pago_diario', 'horas_base')->find($id)
        );
    }

    public function obtenerPermisos(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::with('permisos')
            ->findOrFail($request->user_id);

        $permisosAsignados = $user->permisos
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

                $codigoGrupo = Str::slug($grupo, '_');

                $modulos = $permisosGrupo
                    ->groupBy('modulo')
                    ->map(function ($permisosModulo, $modulo) use ($permisosAsignados) {

                        return [

                            'modulo' => $modulo,

                            'permisos' => $permisosModulo
                                ->map(function ($permiso) use ($permisosAsignados) {

                                    return [

                                        'id' => $permiso->id,

                                        'nombre' => $permiso->nombre,

                                        'codigo' => $permiso->codigo,

                                        'accion' => ucfirst(
                                            $permiso->accion
                                        ),

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
            'estado' => true,
            'permisos' => $resultado,
        ]);
    }

    public function guardarPermisos(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permisos' => 'nullable|array',
            'permisos.*' => 'exists:permisos,id',
        ]);

        $user = User::findOrFail(
            $request->user_id
        );

        $user->permisos()->sync(
            $request->permisos ?? []
        );

        return response()->json([
            'estado' => true,
            'message' => 'Permisos actualizados correctamente.',
        ]);
    }

}
