@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tamanio_boton {
            font-size: 6px;
        }
    </style>
@endsection
@section('metadatos')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')

    <!--begin::Modal - Add task-->
    <div class="modal fade" id="modalUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">FORMULARIO DE USUARIO <span class="text-info" id="nombre_busqueda"></span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formularioUser">
                        <input type="hidden" name="id" id="id" value="0">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Sucursal</label>
                                    <select class="form-control form-control-sm" id="sucursal_id" name="sucursal_id" required>
                                        <option value="">Seleccione una Sucursal</option>
                                            @forelse($sucursales as $sucursal)
                                                <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                            @empty
                                                <h4 class="text-danger">No hay sucursales registrados</h4>
                                           @endforelse
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Rol</label>
                                    <select class="form-control form-control-sm" id="rol_id" name="rol_id" required>
                                        <option value="">Seleccione un rol</option>
                                            @forelse($roles as $rol)
                                                <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                                            @empty
                                                <h4 class="text-danger">No hay Roles registrado</h4>
                                           @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Nombre</label>
                                    <input type="text" class="form-control form-control-sm" id="nombre"
                                        name="nombre">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Apellido paterno</label>
                                    <input type="text" class="form-control form-control-sm" id="ap_paterno"
                                        name="ap_paterno">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Apellido materno</label>
                                    <input type="text" class="form-control form-control-sm" id="ap_materno"
                                        name="ap_materno">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Cedula Identidad</label>
                                    <input type="text" class="form-control form-control-sm" id="cedula"
                                        name="cedula" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Celular</label>
                                    <input type="text" class="form-control form-control-sm" id="celular"
                                        name="celular" maxlength="8">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Nombre Usuario</label>
                                    <input type="text" class="form-control form-control-sm" id="name"
                                        name="name">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="email"
                                        name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">password</label>
                                    <input type="password" class="form-control form-control-sm" id="password"
                                        name="password">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm w-100 btn-success" onclick="guardarUser()">Guardar</button>
                        </div>
                    </div>
                </div>
                <!--end::Modal body-->
            </div>
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Add task-->

    <div class="modal fade" id="modalPermisosUsuario" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered modal-xl">

            <div class="modal-content">

                <div class="modal-header bg-light-primary">

                    <div>
                        <h3 class="fw-bold mb-1">
                            PERMISOS DEL USUARIO
                        </h3>

                        <span class="text-primary fw-bold" id="nombreUsuarioPermiso">
                        </span>
                    </div>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <input type="hidden" id="permiso_user_id">


                    <div id="loadingPermisos" class="text-center py-10">

                        <span class="spinner-border text-primary"></span>

                        <div class="mt-3">
                            Cargando permisos...
                        </div>

                    </div>


                    <div id="contenidoPermisos" style="display:none;">

                        <div class="d-flex justify-content-end mb-5">

                            <button type="button" class="btn btn-sm btn-light-success me-2" onclick="marcarTodosPermisos()">

                                <i class="fa fa-check-double"></i>
                                Marcar todos

                            </button>

                            <button type="button" class="btn btn-sm btn-light-danger" onclick="desmarcarTodosPermisos()">

                                <i class="fa fa-times"></i>
                                Desmarcar todos

                            </button>

                        </div>


                        <div id="listaPermisos"></div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button type="button" class="btn btn-primary" onclick="guardarPermisosUsuario()">

                        <i class="fa fa-save"></i>
                        Guardar Permisos

                    </button>

                </div>

            </div>

        </div>

    </div>

    <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxlg">
            <div class="card shadow-sm">
                <div class="card-header bg-light-info py-4 d-flex align-items-center justify-content-between">
                    <h3 class="card-title fw-bold">Listado de Usuario</h3>
                    <div class="card-toolbar">
                        @can('usuarios.crear')
                        <button type="button" class="btn btn-primary btn-sm" onclick="modalNuevoUser()">
                            <i class="fa fa-plus"></i> Nuevo Usuario
                        </button>
                        @endcan
                    </div>
                </div>

                <div class="card-body py-4" id="table_listado">
                    <!-- El listado se carga por AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>


@stop()

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>

        $.ajaxSetup({
            // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $(document).ready(function() {
            ajaxListado();
        });


        function ajaxListado(){
            let datos = {};
            $.ajax({
                url: "{{ route('user.ajaxListado') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {

                    if (resultado.estado) {
                        $('#table_listado').html(resultado.data.listado)
                    } else {

                    }
                    // Ocultar SweetAlert2 cuando la solicitud sea exitosa
                    // Swal.close();
                }
            })
        }

        function modalNuevoUser(){
            $('#password').val('')
            $('#email').val('')
            $('#name').val('')
            $('#celular').val('')
            $('#cedula').val('')
            $('#ap_materno').val('')
            $('#ap_paterno').val('')
            $('#nombre').val('')
            $('#rol_id').val('')
            $('#sucursal_id').val('')
            $('#id').val(0)
            $('#modalUsuario').modal('show')
        }

        function guardarUser(){

            if($('#formularioUser')[0].checkValidity()){
                let datos = $('#formularioUser').serializeArray();

                 $.ajax({
                    url: "{{ route('user.guardarUser') }}",
                    method: "POST",
                    data: datos,
                    success: function(resultado) {
                        if (resultado.estado) {
                            Swal.fire({
                                title: "EL REGISTRO FUE EXITOSO.",
                                icon: "success",
                                timer: 3000, // Se cierra en 3 segundos
                                showConfirmButton: false
                            });
                            ajaxListado();
                            $('#modalUsuario').modal('hide');
                        } else {

                        }
                    },
                    error: function(xhr) {
                        limpiarErorres();

                        if (xhr.status === 422) {
                            let errores = xhr.responseJSON.errors;

                            for (let campo in errores) {
                                let mensaje = errores[campo][0];

                                let input = $(`[name="${campo}"]`);
                                input.addClass("is-invalid");
                                input.after(`<div class="invalid-feedback">${mensaje}</div>`);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error inesperado.',
                            });
                        }
                    }
                });
            }else{
                $("#formularioUser")[0].reportValidity();
            }

        }

        function editarUser(user){
            $('#password').val(user.password)
            $('#email').val(user.email)
            $('#name').val(user.name)
            $('#celular').val(user.celular)
            $('#cedula').val(user.cedula)
            $('#ap_materno').val(user.ap_materno)
            $('#ap_paterno').val(user.ap_paterno)
            $('#nombre').val(user.nombres)
            $('#rol_id').val(user.rol_id)
            $('#sucursal_id').val(user.sucursal_id)
            $('#id').val(user.id)
            $('#modalUsuario').modal('show')
        }

        function eliminarUser(user, name) {
            Swal.fire({
                title: "¿Quieres eliminar " + name + "?",
                text: "¡No podrás recuperarlo!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Sí, borrar",
                cancelButtonText: "No, cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('user.eliminarUser') }}",
                        method: "POST",
                        data: { user: user },
                        success: function(resultado) {
                            if (resultado.estado) {
                                ajaxListado(); // recarga el listado
                                Swal.fire(
                                    'Eliminado!',
                                    'El usuario ha sido eliminado correctamente.',
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Error',
                                    resultado.message || 'No se pudo eliminar el usuario.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error inesperado.'
                            });
                        }
                    });


                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelado',
                        'La operación fue cancelada',
                        'info'
                    );
                }
            });
        }

        // function modalPermisos(rolId, nombreRol) {
        //     $('#permiso_rol_id').val(rolId);
        //     $('#nombreRolPermiso').text(nombreRol);

        //     $('#listaPermisos').html('');
        //     $('#contenidoPermisos').hide();
        //     $('#loadingPermisos').show();

        //     $('#modalPermisos').modal('show');

        //     $.ajax({

        //         url: "{{ route('rol.obtenerPermisos') }}",

        //         method: "POST",

        //         data: {
        //             rol_id: rolId
        //         },

        //         success: function(resultado) {

        //             $('#loadingPermisos').hide();

        //             if (!resultado.estado) {

        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Error',
        //                     text: resultado.message ?? 'No se pudieron obtener los permisos.'
        //                 });

        //                 return;
        //             }

        //             construirPermisos(resultado.permisos);

        //             $('#contenidoPermisos').show();
        //         },

        //         error: function(xhr) {

        //             $('#loadingPermisos').hide();

        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Error',
        //                 text: xhr.responseJSON?.message ?? 'No se pudieron cargar los permisos.'
        //             });
        //         }
        //     });
        // }

        function modalPermisosUsuario(userId, nombreUsuario) {

            $('#permiso_user_id').val(userId);

            $('#nombreUsuarioPermiso').text(nombreUsuario);

            $('#listaPermisos').html('');

            $('#contenidoPermisos').hide();

            $('#loadingPermisos').show();

            $('#modalPermisosUsuario').modal('show');


            $.ajax({

                url: "{{ route('user.obtenerPermisos') }}",

                method: "POST",

                data: {
                    user_id: userId
                },

                success: function(resultado) {

                    $('#loadingPermisos').hide();

                    if (!resultado.estado) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: resultado.message
                        });

                        return;
                    }

                    construirPermisos(resultado.permisos);

                    $('#contenidoPermisos').show();
                },

                error: function(xhr) {

                    $('#loadingPermisos').hide();

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message ??
                            'No se pudieron cargar los permisos.'
                    });

                }

            });
        }

        function construirPermisos(permisos) {

            let html = '';

            permisos.forEach(function(grupo) {

                html += `
                    <div class="card border mb-5">

                        <div class="card-header min-h-50px bg-light">

                            <div class="card-title">

                                <h4 class="fw-bold mb-0">
                                    ${grupo.grupo}
                                </h4>

                            </div>

                            <div class="card-toolbar">

                                <label class="form-check form-check-sm form-check-custom">

                                    <input type="checkbox"
                                        class="form-check-input check-grupo"
                                        onchange="marcarGrupo(this, '${grupo.codigo_grupo}')">

                                    <span class="form-check-label fw-semibold">
                                        Seleccionar grupo
                                    </span>

                                </label>

                            </div>

                        </div>

                        <div class="card-body py-4">
                `;


                grupo.modulos.forEach(function(modulo) {

                    html += `

                        <div class="row border-bottom py-3 align-items-center">

                            <div class="col-md-4">

                                <strong>
                                    ${modulo.modulo}
                                </strong>

                            </div>

                            <div class="col-md-8">

                                <div class="d-flex flex-wrap gap-5">
                    `;


                    modulo.permisos.forEach(function(permiso) {

                        let checked = permiso.asignado
                            ? 'checked'
                            : '';

                        html += `

                            <label class="form-check form-check-sm form-check-custom form-check-solid">

                                <input
                                    class="form-check-input permiso-check grupo-${grupo.codigo_grupo}"
                                    type="checkbox"
                                    value="${permiso.id}"
                                    ${checked}>

                                <span class="form-check-label">
                                    ${permiso.accion}
                                </span>

                            </label>

                        `;
                    });


                    html += `
                                </div>

                            </div>

                        </div>
                    `;
                });


                html += `

                        </div>

                    </div>
                `;
            });


            $('#listaPermisos').html(html);
        }

        function marcarGrupo(check, grupo) {
            $('.grupo-' + grupo).prop('checked', check.checked);
        }

        function marcarTodosPermisos() {
            $('.permiso-check').prop('checked', true);
            $('.check-grupo').prop('checked', true);
        }

        function desmarcarTodosPermisos() {
            $('.permiso-check').prop('checked', false);
            $('.check-grupo').prop('checked', false);
        }

        function guardarPermisosRol() {
            let rolId = $('#permiso_rol_id').val();

            let permisos = [];

            $('.permiso-check:checked').each(function() {

                permisos.push(
                    $(this).val()
                );

            });


            Swal.fire({

                title: '¿Guardar permisos?',

                text: 'Los permisos se aplicarán a todos los usuarios que tengan este rol.',

                icon: 'question',

                showCancelButton: true,

                confirmButtonText: 'Sí, guardar',

                cancelButtonText: 'Cancelar'

            }).then((result) => {

                if (!result.isConfirmed) {
                    return;
                }


                $.ajax({

                    url: "{{ route('rol.guardarPermisos') }}",

                    method: "POST",

                    data: {
                        rol_id: rolId,
                        permisos: permisos
                    },

                    success: function(resultado) {

                        if (resultado.estado) {

                            $('#modalPermisos').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Permisos actualizados',
                                text: 'Los permisos del rol fueron actualizados correctamente.',
                                timer: 2500,
                                showConfirmButton: false
                            });

                        } else {

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: resultado.message ?? 'No se pudieron guardar los permisos.'
                            });

                        }

                    },

                    error: function(xhr) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: xhr.responseJSON?.message ?? 'Ocurrió un error al guardar los permisos.'
                        });

                    }

                });

            });
        }

        function guardarPermisosUsuario() {

            let userId = $('#permiso_user_id').val();

            let permisos = [];


            $('.permiso-check:checked').each(function() {

                permisos.push(
                    $(this).val()
                );

            });


            $.ajax({

                url: "{{ route('user.guardarPermisos') }}",

                method: "POST",

                data: {

                    user_id: userId,

                    permisos: permisos

                },

                success: function(resultado) {

                    if (resultado.estado) {

                        $('#modalPermisosUsuario').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Permisos actualizados',
                            text: resultado.message,
                            timer: 2500,
                            showConfirmButton: false
                        });

                    }

                },

                error: function(xhr) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: xhr.responseJSON?.message ??
                            'No se pudieron guardar los permisos.'
                    });

                }

            });
        }

    </script>
@endsection
