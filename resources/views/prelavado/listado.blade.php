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
    <div class="modal fade" id="modalPrelavado" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">FORMULARIO DE PRELAVADO <span class="text-info" id="nombre_busqueda"></span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formularioPrelavado">
                        <input type="hidden" name="id" id="id" value="0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Nombre</label>
                                    <input type="text" class="form-control form-control-sm" id="nombre"
                                        name="nombre">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm w-100 btn-success" onclick="guardarPrelavado()">Guardar</button>
                        </div>
                    </div>
                </div>
                <!--end::Modal body-->
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
                    <h3 class="card-title fw-bold">Listado de Prelavados</h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary btn-sm" onclick="modalNuevoPrelavado()">
                            <i class="fa fa-plus"></i> Nuevo Prelavado
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
                url: "{{ route('prelavado.ajaxListado') }}",
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

        function modalNuevoPrelavado(){
            $('#nombre').val('')
            $('#id').val(0)
            $('#modalPrelavado').modal('show')
        }

        function guardarPrelavado(){
            let datos = $('#formularioPrelavado').serializeArray();

             $.ajax({
                url: "{{ route('prelavado.guardarPrelavado') }}",
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
                        $('#modalPrelavado').modal('hide');
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

        function editarPrelavado(prelavado){

            $('#nombre').val(prelavado.nombre)
            $('#id').val(prelavado.id)
            $('#modalPrelavado').modal('show')
            
        }

        function eliminarPrelavado(prelavado, nombre) {
            Swal.fire({
                title: "¿Quieres eliminar " + nombre + "?",
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
                        url: "{{ route('prelavado.eliminarPrelavado') }}",
                        method: "POST",
                        data: { prelavado: prelavado },
                        success: function(resultado) {
                            if (resultado.estado) {
                                ajaxListado(); // recarga el listado
                                Swal.fire(
                                    'Eliminado!',
                                    'El tipo De proceso ha sido eliminado correctamente.',
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Error',
                                    resultado.message || 'No se pudo eliminar el Tipo de proceso.',
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
        
    </script>
@endsection
