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
    <div class="modal fade" id="modalRol" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">FORMULARIO DE SOLICITUD</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formularioRol">
                        <input type="hidden" name="id" id="id" value="0">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Producto</label>
                                    <select class="form-select form-select-sm" name="producto_id" id="producto_id">
                                        @foreach ( $productos as $producto)
                                        <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Cantidad</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="cantidad" id="cantidad">
                                </div>
                            </div>
                            {{-- <div class="col-md-2">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Porcentaje</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="porcentaje" id="porcentaje">
                                </div>
                            </div> --}}
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm w-100 btn-success" onclick="guardarRol()">Guardar</button>
                        </div>
                    </div>
                </div>
                <!--end::Modal body-->
            </div>
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Add task-->

    <!--begin::Modal - Add task-->
    <div class="modal fade" id="modalListaPreparacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">LISTADO DE PREPARACIONES para el N° <span class="text-info" id="text-numero-preparacion"></span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <div id="tabla-preparaciones-solicitud"></div>
                </div>
                <!--end::Modal body-->
            </div>
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Add task-->

    <!--begin::Modal - Add task-->
    <div class="modal fade" id="modalNuevoPreparacion" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">FORMULARIO DE NUEVA PREPARACION</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formularioNuevoPreparacion">
                        <input type="hidden" name="solicitud_id_padre" id="solicitud_id_padre" value="0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Cantidad Liquido</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="cantidad_liquido_preceso" id="cantidad_liquido_preceso" required/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--end::Modal body-->
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm w-100 btn-success" onclick="guardarNuevoProceso()">Guardar Preparacion</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Add task-->

    <!--begin::Modal - Add task-->
    <div class="modal fade" id="modalDivicionCarga" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">FORMULARIO DE DIVICION PARA CARGA</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formularioDivicionCarga">

                        <input type="hidden" name="preparacion_id_divicion" id="preparacion_id_divicion" value="0">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Total</label>
                                            <input class="form-control form-control-sm bg-primary" name="total_carga" id="total_carga" required readonly/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Utilizado</label>
                                            <input class="form-control form-control-sm bg-warning" name="utilizado_carga" id="utilizado_carga" required readonly/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="fv-row mb-7">
                                            <label class="required fw-semibold fs-6 mb-2">Disponible</label>
                                            <input class="form-control form-control-sm bg-success" name="disponible_carga" id="disponible_carga" required readonly/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Carga</label>
                                    <select class="form-select form-select-sm" name="solicitud_id_preceso_carga" id="solicitud_id_preceso_carga" required></select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Cantidad</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" min="1" name="cantidad_preceso_carga" id="cantidad_preceso_carga" required/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Cant. Liquido</label>
                                    <input type="number" step="0.01" class="form-control form-control-sm" name="cantidad_liquido_preceso_carga" id="cantidad_liquido_preceso_carga" required/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <!--end::Modal body-->
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm w-100 btn-success" onclick="guardarDivicacionCarga()">Guardar Preparacion</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - Add task-->

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxlg">
                <div class="card shadow-sm">
                    <div class="card-header bg-light-info py-4 d-flex align-items-center justify-content-between">
                        <h3 class="card-title fw-bold">Listado de Solicitudes para Focalizados</h3>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-primary btn-sm" onclick="modalNuevoRol()">
                                <i class="fa fa-plus"></i> Nuevo Solicitud
                            </button>
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
                url: "{{ route('procesos.ajaxListadoSolicitudFocalizado') }}",
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

        function modalNuevoRol(){
            $('#nombre').val('')
            $('#id').val(0)
            $('#modalRol').modal('show')
        }

        function guardarRol(){
            let datos = $('#formularioRol').serializeArray();

             $.ajax({
                url: "{{ route('procesos.guardarSolicitudFocalizado') }}",
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
                        $('#modalRol').modal('hide');
                    } else {

                    }
                },
                error: function(xhr) {
                    // limpiarErorres();

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
        }

        function modalPreparaciones(solicitud){

            $.ajax({
                url: "{{ route('procesos.ajaxlistadoPreparaciones') }}",
                method: "POST",
                data: {solicitud:solicitud},
                success: function(resultado) {
                    if (resultado.estado) {

                        $('#text-numero-preparacion').text(solicitud)
                        $('#tabla-preparaciones-solicitud').html(resultado.data.listado)
                        $('#modalListaPreparacion').modal('show')

                        // Swal.fire({
                        //     title: "EL REGISTRO FUE EXITOSO.",
                        //     icon: "success",
                        //     timer: 3000, // Se cierra en 3 segundos
                        //     showConfirmButton: false
                        // });
                        // ajaxListado();
                        // $('#modalRol').modal('hide');
                    } else {

                    }
                },
                error: function(xhr) {
                    // limpiarErorres();

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
        }

        function modalNuevoPreparacion(solicitud){

            // $.ajax({
            //     url: "{{ route('procesos.ajaxListadoSolicitudesFocalizado') }}",
            //     method: "POST",
            //     data: {solicitud:solicitud},
            //     success: function(resultado) {
            //         if (resultado.estado) {

            //             // let select = $('#solicitud_id_preceso');
            //             // select.empty();
            //             // select.append('<option value="">-- Seleccione --</option>');
            //             // let datos = resultado.data.solicitudArray;
            //             // Object.entries(datos).forEach(([key, value]) => {
            //             //     select.append(
            //             //         `<option value="${key}">
            //             //             ${value}
            //             //         </option>`
            //             //     );
            //             // });


            //         } else {

            //         }
            //     },
            //     error: function(xhr) {
            //         limpiarErorres();

            //         if (xhr.status === 422) {
            //             let errores = xhr.responseJSON.errors;

            //             for (let campo in errores) {
            //                 let mensaje = errores[campo][0];

            //                 let input = $(`[name="${campo}"]`);
            //                 input.addClass("is-invalid");
            //                 input.after(`<div class="invalid-feedback">${mensaje}</div>`);
            //             }
            //         } else {
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'Error',
            //                 text: 'Ocurrió un error inesperado.',
            //             });
            //         }
            //     }
            // });

            $('#solicitud_id_padre').val(solicitud)
            $('#modalListaPreparacion').modal('hide')
            $('#modalNuevoPreparacion').modal('show')
        }

        function guardarNuevoProceso(){
            if($('#formularioNuevoPreparacion')[0].checkValidity()){
                let datos = $('#formularioNuevoPreparacion').serializeArray();
                $.ajax({
                    url: "{{ route('procesos.guardarNuevoProcesoPadre') }}",
                    method: "POST",
                    data: datos,
                    success: function(resultado) {
                        if (resultado.estado) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se creo con exito.',
                            });
                            $('#tabla-preparaciones-solicitud').html(resultado.data.listado);
                            $('#modalNuevoPreparacion').modal('hide');
                            $('#modalListaPreparacion').modal('show');
                        } else {

                        }
                    },
                    error: function(xhr) {
                        // limpiarErorres();

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
                $("#formularioNuevoPreparacion")[0].reportValidity();
            }
        }

        function modalDivicionPreparacionCarga(preparacion, total, totalHasta){

            console.log(preparacion, total, totalHasta);

            $.ajax({
                url: "{{ route('procesos.ajaxListadoSolicitudesFocalizado') }}",
                method: "POST",
                success: function(resultado) {
                    if (resultado.estado) {

                        let select = $('#solicitud_id_preceso_carga');
                        select.empty();
                        select.append('<option value="">-- Seleccione --</option>');
                        let datos = resultado.data.solicitudArray;
                        Object.entries(datos).forEach(([key, value]) => {
                            select.append(
                                `<option value="${key}">
                                    ${value}
                                </option>`
                            );
                        });

                        $('#cantidad_preceso_carga').prop('max', total - totalHasta)
                        $('#disponible_carga').val(total - totalHasta);
                        $('#total_carga').val(total);
                        $('#utilizado_carga').val(totalHasta);
                        $('#preparacion_id_divicion').val(preparacion);
                        $('#modalListaPreparacion').modal('hide');
                        $('#cantidad_preceso_carga').val(0)
                        $('#cantidad_liquido_preceso_carga').val(0)
                        $('#modalDivicionCarga').modal('show');

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

        }

        function guardarDivicacionCarga(){
            if($('#formularioDivicionCarga')[0].checkValidity()){
                let datos = $('#formularioDivicionCarga').serializeArray();
                $.ajax({
                    url: "{{ route('procesos.guardarDivicacionCarga') }}",
                    method: "POST",
                    data: datos,
                    success: function(resultado) {
                        if (resultado.estado) {

                            $('#tabla-preparaciones-solicitud').html(resultado.data.listado);
                            $('#modalNuevoPreparacion').modal('hide');
                            $('#modalDivicionCarga').modal('hide');
                            $('#modalListaPreparacion').modal('show');

                            Swal.fire({
                                icon: 'success',
                                title: 'Exito',
                                text: 'Se creo con exito.',
                            });
                        } else {

                        }
                    },
                    error: function(xhr) {
                        // limpiarErorres();

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
                $("#formularioDivicionCarga")[0].reportValidity();
            }
        }

    </script>
@endsection
