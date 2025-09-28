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
    <div class="modal fade" id="modalCliente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">FORMULARIO DE CLIENTE <span class="text-info" id="nombre_busqueda"></span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formularioCliente">
                        <input type="hidden" name="id" id="id" value="0">
                        <div class="row">
                            <div class="col-md-12">                            
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Nombre</label>
                                    <input type="text" class="form-control form-control-sm" id="nombre"
                                        name="nombre">
                                </div>                     
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Apellido paterno</label>
                                    <input type="text" class="form-control form-control-sm" id="ap_paterno"
                                        name="ap_paterno">
                                </div>                     
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Apellido materno</label>
                                    <input type="text" class="form-control form-control-sm" id="ap_materno"
                                        name="ap_materno">
                                </div>                                                                   
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Celular</label>
                                    <input type="text" class="form-control form-control-sm" id="celular"
                                        name="celular" maxlength="8">
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Cedula Identidad</label>
                                    <input type="text" class="form-control form-control-sm" id="cedula"
                                        name="cedula" maxlength="10">
                                </div>                                                  
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">NIT</label>
                                    <input type="text" class="form-control form-control-sm" id="nit"
                                        name="nit">
                                </div>                                                      
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Razon Social</label>
                                    <input type="text" class="form-control form-control-sm" id="razon_social"
                                        name="razon_social">
                                </div>                                  
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm w-100 btn-success" onclick="guardarCliente()">Guardar</button>
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
                    <h3 class="card-title fw-bold">Listado de Cliente</h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary btn-sm" onclick="modalNuevoCliente()">
                            <i class="fa fa-plus"></i> Nuevo Cliente
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
                url: "{{ route('cliente.ajaxListado') }}",
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

        function modalNuevoCliente(){
            $('#nombre').val('')
            $('#ap_materno').val('')
            $('#id').val(0)
            $('#modalCliente').modal('show')
        }

       
       

      
        
    </script>
@endsection
