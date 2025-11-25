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
    <div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxlg">
            <div class="card shadow-sm">
                <div class="card-header bg-light-info py-4 d-flex align-items-center justify-content-between">
                    <h3 class="card-title fw-bold">REPORTES</h3>
                    {{-- <div class="card-toolbar">
                        <button type="button" class="btn btn-primary btn-sm" onclick="modalNuevoRol()">
                            <i class="fa fa-plus"></i> Nuevo Rol
                        </button>
                    </div> --}}
                </div>
                <div class="card-body py-4" id="table_listado">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-flush shadow-sm">
                                <div class="card-header bg-light-danger">
                                    <h3 class="card-title">Reporte de Cuentas por Cobrar</h3>
                                    {{-- <div class="card-toolbar">
                                        <button type="button" class="btn btn-sm btn-light">
                                            Action
                                        </button>
                                    </div> --}}
                                </div>
                                <div class="card-body py-5">
                                    <form action="{{ route('reporte.cuentaPorCobrar') }}" method="POST" target="_blank">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="fv-row mb-7">
                                                    <label class="required fw-semibold fs-6 mb-2">Cliente</label>
                                                    <select class="form-select form-select-sm" name="cliente_id" id="cliente_id">
                                                        @foreach ($clientes as $cliente)
                                                        <option value="{{ $cliente->id }}">{{ $cliente->nombres." ".$cliente->ap_paterno." ".$cliente->ap_materno }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button class="btn btn-success btn-sm w-100 mt-8" title="Generar Reporte"><i class="fa fa-file"></i></button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                {{-- <div class="card-footer">
                                    Footer
                                </div> --}}
                            </div>
                        </div>
                    </div>
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
            // ajaxListado();
        });


        // function ajaxListado(){
        //     let datos = {};
        //     $.ajax({
        //         url: "{{ route('rol.ajaxListado') }}",
        //         method: "POST",
        //         data: datos,
        //         success: function(resultado) {

        //             if (resultado.estado) {
        //                 $('#table_listado').html(resultado.data.listado)
        //             } else {

        //             }
        //         }
        //     })
        // }

        // function modalNuevoRol(){
        //     $('#nombre').val('')
        //     $('#id').val(0)
        //     $('#modalRol').modal('show')
        // }

        // function guardarRol(){
        //     let datos = $('#formularioRol').serializeArray();

        //      $.ajax({
        //         url: "{{ route('rol.guardarRol') }}",
        //         method: "POST",
        //         data: datos,
        //         success: function(resultado) {
        //             if (resultado.estado) {
        //                 Swal.fire({
        //                     title: "EL REGISTRO FUE EXITOSO.",
        //                     icon: "success",
        //                     timer: 3000, // Se cierra en 3 segundos
        //                     showConfirmButton: false
        //                 });
        //                 ajaxListado();
        //                 $('#modalRol').modal('hide');
        //             } else {

        //             }
        //         },
        //         error: function(xhr) {
        //             limpiarErorres();

        //             if (xhr.status === 422) {
        //                 let errores = xhr.responseJSON.errors;

        //                 for (let campo in errores) {
        //                     let mensaje = errores[campo][0];

        //                     let input = $(`[name="${campo}"]`);
        //                     input.addClass("is-invalid");
        //                     input.after(`<div class="invalid-feedback">${mensaje}</div>`);
        //                 }
        //             } else {
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Error',
        //                     text: 'Ocurrió un error inesperado.',
        //                 });
        //             }
        //         }
        //     });
        // }

        // function editarRol(rol){

        //     $('#nombre').val(rol.nombre)
        //     $('#id').val(rol.id)
        //     $('#modalRol').modal('show')

        // }

        // function eliminarRol(rol, nombre) {
        //     Swal.fire({
        //         title: "¿Quieres eliminar " + nombre + "?",
        //         text: "¡No podrás recuperarlo!",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: "Sí, borrar",
        //         cancelButtonText: "No, cancelar",
        //         reverseButtons: true
        //     }).then((result) => {
        //         if (result.isConfirmed) {

        //             $.ajax({
        //                 url: "{{ route('rol.eliminarRol') }}",
        //                 method: "POST",
        //                 data: { rol: rol },
        //                 success: function(resultado) {
        //                     if (resultado.estado) {
        //                         ajaxListado(); // recarga el listado
        //                         Swal.fire(
        //                             'Eliminado!',
        //                             'El rol ha sido eliminado correctamente.',
        //                             'success'
        //                         );
        //                     } else {
        //                         Swal.fire(
        //                             'Error',
        //                             resultado.message || 'No se pudo eliminar el rol.',
        //                             'error'
        //                         );
        //                     }
        //                 },
        //                 error: function(xhr) {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Error',
        //                         text: 'Ocurrió un error inesperado.'
        //                     });
        //                 }
        //             });


        //         } else if (result.dismiss === Swal.DismissReason.cancel) {
        //             Swal.fire(
        //                 'Cancelado',
        //                 'La operación fue cancelada',
        //                 'info'
        //             );
        //         }
        //     });
        // }

    </script>
@endsection
