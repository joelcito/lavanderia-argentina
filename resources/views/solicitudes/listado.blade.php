@extends('layouts.app') {{-- Ajusta según tu layout --}}
@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tamanio_boton {
            font-size: 6px;
        }
    </style>
@endsection
@section('title', 'Listado de Solicitudes')
@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tamanio_boton {
            font-size: 6px;
        }

        .maquina-container {
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin: 0 10px;
        }

        .maquina-container img {
            width: 80px;
            height: 80px;
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

                <h3 class="mb-4">Aprobación de Solicitudes</h3>

                <div class="card">
                    <div class="card-body">
                        <!-- Tabla de solicitudes -->
                        <div id="tablaSolicitudes">
                            <p class="text-center">Cargando...</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Modal para ver detalle de solicitud (opcional) --}}
        <div class="modal fade" id="modalDetalleSolicitud" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Detalle de Solicitud para la Factura/Orden Recepcion <span class="text-white" id="numero_recepcion_texto"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body" id="detalleSolicitudBody">
                        <!-- Contenido cargado dinámicamente -->


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

@endsection

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
        <script>
            $(document).ready(function () {
                cargarSolicitudes();
            });

            function cargarSolicitudes() {
                $.ajax({
                    url: "{{ route('solicitudes.ajaxListado') }}",
                    method: "POST",
                    data: {},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {
                        if (res.estado) {
                            $('#tablaSolicitudes').html(res.data.listado);
                        } else {
                            $('#tablaSolicitudes').html('<p class="text-danger text-center">Error al cargar solicitudes</p>');
                        }
                    },
                    error: function (xhr) {
                        console.error(xhr);
                        $('#tablaSolicitudes').html('<p class="text-danger text-center">Error interno al cargar solicitudes</p>');
                    }
                });
            }

            // Función opcional para abrir modal de detalle
            // function verDetalleSolicitud(id) {
            //     $.get("/solicitud/detalle/" + id, function (data) {
            //         $('#detalleSolicitudBody').html(data);
            //         $('#modalDetalleSolicitud').modal('show');
            //     });
            // }

            // function abrirModalOT(otId) {
            //     $.ajax({
            //         url: "{{ route('solicitudes.ajaxDetalleOT') }}",
            //         method: "POST",
            //         data: { ot_id: otId },
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         success: function (res) {
            //             if (res.estado) {
            //                 let html = `<table class="table table-bordered table-striped">
            //                                                                 <thead>
            //                                                                     <tr>
            //                                                                         <th>Producto</th>
            //                                                                         <th>Cantidad</th>
            //                                                                         <th>Estado</th>
            //                                                                         <th>Usuario</th>
            //                                                                     </tr>
            //                                                                 </thead>
            //                                                                 <tbody>`;
            //                 res.data.solicitudes.forEach(s => {
            //                     html += `<tr>
            //                                                             <td>${s.producto}</td>
            //                                                             <td>${s.cantidad}</td>
            //                                                             <td>${s.estado}</td>
            //                                                             <td>${s.usuario}</td>
            //                                                            <td>
            //                     ${s.estado === 'EN PROCESO' ? `
            //                         <button class="btn btn-success btn-sm" onclick="accionSolicitud(${s.id}, 'aprobar')">Aprobar</button>
            //                         <button class="btn btn-danger btn-sm" onclick="accionSolicitud(${s.id}, 'rechazar')">Rechazar</button>
            //                     ` : ''}
            //                 </td>
            //                                                         </tr>`;
            //                 });
            //                 html += `</tbody></table>`;

            //                 $('#detalleSolicitudBody').html(html);
            //                 $('#modalDetalleSolicitud').modal('show');
            //             } else {
            //                 Swal.fire('Error', 'No se pudieron cargar las solicitudes', 'error');
            //             }
            //         },
            //         error: function () {
            //             Swal.fire('Error', 'No se pudo cargar el detalle de la OT', 'error');
            //         }
            //     });
            // }

            function accionSolicitud(solicitudId, accion) {
                let f = $('#ingresos_'+solicitudId).val();
                if(f != null && f != ""){
                    $.ajax({
                        url: "{{ route('solicitudes.accionProducto') }}",
                        type: "POST",
                        data: {
                            solicitud_id: solicitudId,
                            accion      : accion,
                            ingreso     : f
                        },
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function (res) {
                            if (res.estado) {
                                Swal.fire('OK', 'Solicitud ' + accion, 'success');
                                cargarSolicitudes();
                                $('#modalDetalleSolicitud').modal('hide');
                            } else {
                                Swal.fire('Error', res.mensaje, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
                        }
                    });
                }else{
                    Swal.fire('Error', 'Debe seleccionar un ingreso del prodcuto para el descuento del inventario', 'error');
                }
            }

            function verDetalleSolicitud(factura, numero){

                $.ajax({
                    url: "{{ route('solicitudes.verDetalleSolicitud') }}",
                    type: "POST",
                    data: {
                        factura: factura
                    },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (res) {
                        if (res.estado) {

                            let html = `<table class="table table-bordered table-striped">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Producto</th>
                                                                                    <th>Ingreso</th>
                                                                                    <th>N Ot</th>
                                                                                    <th>Cantidad</th>
                                                                                    <th>Estado</th>
                                                                                    <th>Usuario</th>
                                                                                    <th></th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>`;
                            res.data.solicitudes.forEach(s => {

                                let selectIni = '<select id="ingresos_'+s.id+'">';
                                let opctionS  = '<option value="">Seleccione un Ingreso</option>'
                                let selectFin = '</select>';
                                let stockPro  = s.stock;

                                stockPro.forEach(hg =>{
                                    opctionS = opctionS+'<option value="'+hg.ID+'">'+hg.CODIGO_COMPRA+' ( Stock: '+hg.STOCK+' )</option>';
                                })

                                let todoSelect = selectIni+opctionS+selectFin;

                                html += `<tr>
                                            <td>${s.producto}</td>
                                            <td>${todoSelect}</td>
                                            <td>${s.nro_ot}</td>
                                            <td>${s.cantidad}</td>
                                            <td>${s.estado}</td>
                                            <td>${s.usuario}</td>
                                            <td>
                                                ${s.estado === 'EN PROCESO' ? `
                                                    <button class="btn btn-success btn-sm" onclick="accionSolicitud(${s.id}, 'aprobar')">Aprobar</button>
                                                    <button class="btn btn-danger btn-sm" onclick="accionSolicitud(${s.id}, 'rechazar')">Rechazar</button>
                                                ` : ''}
                                            </td>
                                        </tr>`;
                            });
                            html += `</tbody></table>`;
                            $('#numero_recepcion_texto').text("N "+numero)
                            $('#detalleSolicitudBody').html(html);
                            $('#modalDetalleSolicitud').modal('show');
                        } else {
                            Swal.fire('Error', 'No se pudieron cargar las solicitudes', 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'No se pudo procesar la solicitud', 'error');
                    }
                });

            }
        </script>
    @endsection
