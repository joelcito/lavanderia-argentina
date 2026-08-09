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
                <h3 class="fw-bold">FORMULARIO DE ROL <span class="text-info" id="nombre_busqueda"></span></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body scroll-y">
                <form id="formularioRol">
                    <input type="hidden" name="id" id="id" value="0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Nombre</label>
                                <input type="text" class="form-control form-control-sm" id="nombre" name="nombre">
                            </div>
                        </div>
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

<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxlg">
            <div class="card shadow-sm">
                <div class="card-header bg-light-info py-4 d-flex align-items-center justify-content-between">
                    <h3 class="card-title fw-bold">Listado de Cargas en Planchado</h3>
                    {{-- <div class="card-toolbar">
                        <button type="button" class="btn btn-primary btn-sm" onclick="modalNuevoRol()">
                            <i class="fa fa-plus"></i> Nuevo Rol
                        </button>
                    </div> --}}
                </div>

                <div class="card-body py-4" id="table_listado">
                    <!-- El listado se carga por AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalPlanchado" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header bg-light-success">
                <h5 class="modal-title">Planchado por Prendas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="solicitud_id">


                <div id="contenedor_planchado"></div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" onclick="guardarPlanchadoDetalle()">Guardar</button>
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

        $(document).ready(function () {
            ajaxListado();
        });


        function ajaxListado() {
            let datos = {};
            $.ajax({
                url: "{{ route('procesos.ajaxListadoSolicitudesPlanchado') }}",
                method: "POST",
                data: datos,
                success: function (resultado) {

                    if (resultado.estado) {
                        $('#table_listado').html(resultado.data.listado)
                    } else {

                    }
                    // Ocultar SweetAlert2 cuando la solicitud sea exitosa
                    // Swal.close();
                }
            })
        }

        function finalizarPlanchado(solicitud) {

            Swal.fire({
                title: "Esta seguro de finalizar el proceso?",
                text: "No podras revertir eso!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Finalizar!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('procesos.finalizarProcesoPlanchado') }}",
                        method: "POST",
                        data: { solicitud: solicitud },
                        success: function (resultado) {
                            if (resultado.estado) {

                                ajaxListado();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Exito',
                                    text: 'Se creo con exito.',
                                });
                            } else {

                            }
                        },
                        error: function (xhr) {
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
            });

        }



        //planchado cantidad

        function abrirModalPlanchado(solicitud_id) {

            console.log("Abrir modal solicitud:", solicitud_id);

            $.get('/procesos/obtener-detalle-planchado/' + solicitud_id, function (res) {
                let html = '';

                res.data.forEach(grupo => {

                    html += `
                                            <div class="card mb-4 p-3 border">
                                                <h5 class="text-primary">Factura: ${grupo.factura_id}</h5>
                                                <div class="row">
                                        `;

                    grupo.ots.forEach(ot => {
                        let restante = ot.restante || 0;
                        html += `
                                                <div class="col-md-4 mb-3 border rounded p-2">

                                                    <strong>OT ${ot.id}</strong>
                                                    <span class="badge bg-success ms-2">Total: ${ot.total}</span>
                     <span class="badge bg-warning ms-2">
                                                            ${restante} restantes
                                                        </span>
                                                    <div class="mt-2">
                                                        <label>${ot.prenda}</label>

                                                        <input 
                                                            type="number"
                                                            class="form-control prenda"
                                                            data-ot="${ot.id}"
                                                            data-factura="${grupo.factura_id}"
                                                            data-categoria="${ot.prenda_id}"
                                                            min="0"
                                                              max="${restante}"
                                                ${restante <= 0 ? 'disabled' : ''}
                                                            placeholder="Cantidad"
                                                        >
                                                    </div>

                                                </div>
                                            `;
                    });

                    html += `</div></div>`;
                });

                $('#contenedor_planchado').html(html);
                $('#solicitud_id').val(solicitud_id);
                $('#modalPlanchado').modal('show');
            });

        }

        function guardarPlanchadoDetalle() {

            let solicitud_id = $('#solicitud_id').val();
            let detalle = [];

            let agrupado = {};
            let error = false;

            $('.prenda').each(function () {

                let ot = $(this).data('ot');
                let factura = $(this).data('factura');
                let categoria = $(this).data('categoria');
                let cantidad = parseInt($(this).val()) || 0;
                let max = parseInt($(this).attr('max')) || 0;

                if (cantidad > max) {
                    error = true;

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `No puedes ingresar más de ${max}`
                    });

                    return false; // rompe each
                }

                if (cantidad > 0) {

                    if (!agrupado[ot]) {
                        agrupado[ot] = {
                            ot_id: ot,
                            factura_id: factura,
                            categorias: {}
                        };
                    }

                    agrupado[ot].categorias[categoria] = cantidad;
                }
            });


            if (error) return;

            detalle = Object.values(agrupado);

            if (detalle.length === 0) {
                Swal.fire('Error', 'Ingrese al menos una cantidad', 'error');
                return;
            }

            $.post("{{ route('procesos.guardarPlanchadoDetalle') }}", {
                solicitud_id: solicitud_id,
                detalle: detalle
            }, function (res) {

                if (res.estado) {
                    Swal.fire('Correcto', res.mensaje, 'success');
                    $('#modalPlanchado').modal('hide');

                    // opcional: recargar listado o modal si quieres
                } else {
                    Swal.fire('Error', res.mensaje, 'error');
                }

            }).fail(function (xhr) {


                let msg = 'Error en el servidor';

                if (xhr.responseJSON && xhr.responseJSON.mensaje) {
                    msg = xhr.responseJSON.mensaje;
                }

                Swal.fire('Error', msg, 'error');
            });
        }



    </script>
@endsection