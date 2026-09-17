@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tamanio_boton {
            font-size: 6px;
        }

        /* .maquina-container {
                                                                cursor: pointer;
                                                                padding: 10px;
                                                                border-radius: 5px;
                                                                text-align: center;
                                                                margin: 0 10px;
                                                            } */

        .maquina-container {
            width: 100px;
            margin: 10px;
            text-align: center;
            cursor: pointer;
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
            <h3 class="fw-bold mb-3">Maquinarias Disponibles</h3>
            <div id="bloque-mquinas"></div>
            <button class="btn btn-sm btn-primary w-100" onclick="abrirModalSolicitud()">
                <i class="fa fa-plus"></i> Solicitar productos
            </button>
            <div class="card shadow-sm">
                <div class="card-header bg-light-info py-4 d-flex align-items-center justify-content-between">
                    <h3 class="card-title fw-bold mb-0">Listado de Procesos de Lavandería</h3>
                </div>
                <div class="card-body py-4" id="table_listado">
                    <!-- Tabla AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ================= MODAL ================= -->
<div class="modal fade" id="modalLavanderia" tabindex="-1" aria-labelledby="modalLavanderiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- modal más ancho -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLavanderiaLabel">Nuevo Proceso</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <!-- Formulario -->
                <form id="formLavanderia" class="row g-3">
                    <input type="hidden" id="id">
                    <input type="hidden" id="maquinaria_id">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="tipo_proceso_id" class="form-label">Tipo de Proceso</label>
                            <select id="tipo_proceso_id" class="form-select" data-control="select2"
                                data-dropdown-parent="#modalLavanderia" data-placeholder="Seleccione tipo de proceso..."
                                onchange="verificaTipoProceso()">
                                <option></option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="producto_solicitud_aprobado" class="form-label">Producto Aprobados</label>
                            <select name="producto_solicitud_aprobado" id="producto_solicitud_aprobado"
                                class="form-select form-select-sm" onchange="buscarSolicitudesProducto()">
                                <option value="">Seleccione producto...</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="producto_solicitud_aprobado" class="form-label">Ordenes Trabajos</label>
                            <select name="ordenes_trabajos_solicitudes_aprobados"
                                id="ordenes_trabajos_solicitudes_aprobados" class="form-select form-select-sm">
                                <option value="">Seleccione solicitud...</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4">
                            <label for="fecha_ingreso" class="form-label">Fecha Ingreso</label>
                            <input type="datetime-local" id="fecha_ingreso" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="tiempo" class="form-label">Tiempo (Minutos)</label>
                            <input type="number" id="tiempo" class="form-control" onkeyup="calcularTiempoFinal()">
                        </div>
                        <div class="col-md-4">
                            <label for="fecha_salida" class="form-label">Fecha Salida</label>
                            <input readonly type="datetime-local" id="fecha_salida" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-2">
                            <label for="temperatura" class="form-label">Temperatura</label>
                            <input type="text" id="temperatura" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label for="ph" class="form-label">PH</label>
                            <input type="text" id="ph" class="form-control">
                        </div>

                        <div class="col-md-2">
                            <label for="rb" class="form-label">RB</label>
                            <input type="text" id="rb" class="form-control">
                        </div>

                        <div class="col-6">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea id="descripcion" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="col-12 text-end mt-5">
                            <button type="button" class="btn btn-primary btn-sm w-100" id="agregarAlListado"><i
                                    class="fa fa-down-long"></i>Agregar al listado</button>
                        </div>
                    </div>
                </form>

                <hr>

                <!-- Tabla Listado Temporal -->
                <div class="table-responsive">
                    <table id="tablaListadoTemporal" class="table table-striped table-bordered mt-2">
                        <thead class="table-primary">
                            <tr>
                                <th>Factura</th>
                                <th>Producto</th>
                                <th>Proceso</th>
                                <th>Fecha Ingreso</th>
                                <th>Fecha Salida</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas se agregan dinámicamente -->
                        </tbody>
                    </table>
                </div>
                <div class="col-12 text-end mt-3">
                    <button type="button" class="btn btn-success btn-sm w-100" id="guardarListado"><i
                            class="fa fa-save"></i>Guardar listado</button>
                </div>

            </div> <!-- modal-body -->
        </div> <!-- modal-content -->
    </div> <!-- modal-dialog -->
</div>

<!-- Modal Solicitud de Productos -->
<div class="modal fade" id="modalSolicitudProductos" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Solicitud de Productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <ul class="nav nav-tabs nav-line-tabs mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_7">NUEVA SOLICITUD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_8"
                            onclick="sacarSolicitudesAgrupados()">SOLICITUD PARA AGRUPADOS</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="kt_tab_pane_7" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label>Facturas / Orden Recibo (puede seleccionar varias)</label>
                                    <select id="facturas_seleccionadas" class="form-select" multiple>
                                        @foreach($facturas as $factura)
                                            <option value="{{ $factura->id }}" data-nro="{{ $factura->numero_factura }}">
                                                {{ $factura->numero_factura }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="check_focalizado">
                                    <label class="form-check-label">
                                        Focalizar
                                    </label>
                                </div>

                                <div id="ots_por_factura_container" class="mb-3"></div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label>Producto (con stock)</label>
                                        <select id="producto_id_solicitud" class="form-select">
                                            <option value="">Seleccione producto...</option>
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Pe. Tot. seleccionado (kg)</label>
                                        <input type="number" id="peso_total_ot" class="form-control" readonly>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Cantidad solicitada (g)</label>
                                        <input type="number" step="0.01" id="cantidad_solicitada" class="form-control">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Porcentaje (%)</label>
                                        <input type="number" step="0.01" id="porcentaje_solicitado"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-success btn-sm w-100" type="button"
                                        id="btnAgregarProducto"><i class="fa fa-down-long"></i>Agregar al
                                        listado</button>
                                </div>
                                <table class="table table-bordered" id="tabla_solicitud_temporal">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Facturas y OTs</th>
                                            <th>Porcentaje (%)</th>
                                            <th>Cantidad</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary btn-sm w-100"
                                            id="btnGuardarSolicitud"><i class="fa fa-save"></i>Guardar
                                            Solicitud</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_8" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <select onchange="sacarPesoTotalSolicitudAgrupado()" class="form-select form-select-sm"
                                    name="solicitud_agrupado_id" id="solicitud_agrupado_id"></select>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Producto (con stock)</label>
                                <select id="producto_id_solicitud_agrupado" class="form-select">
                                    <option value="">Seleccione producto...</option>
                                    @foreach ($productos as $producto)
                                        <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Pe. Tot. seleccionado (kg)</label>
                                <input type="number" id="peso_total_ot_agrupado" class="form-control" readonly>
                            </div>

                            <div class="col-md-3">
                                <label>Cantidad solicitada (g)</label>
                                <input type="number" step="0.01" id="cantidad_solicitada_agrupado" class="form-control"
                                    value="0">
                            </div>

                            <div class="col-md-3">
                                <label>Porcentaje (%)</label>
                                <input type="number" step="0.01" id="porcentaje_solicitado_agrupado"
                                    class="form-control" value="0">
                            </div>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-success btn-sm w-100" type="button"
                                onclick="agregarAlListadoSolicitudAgrupado()" id="btnAgregarProducto"><i
                                    class="fa fa-down-long"></i>Agregar al listado</button>
                        </div>
                        <table class="table table-bordered" id="tabla_solicitud_temporal_agrupado">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Facturas y OTs</th>
                                    <th>Porcentaje (%)</th>
                                    <th>Cantidad</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary btn-sm w-100"
                                    onclick="btnGuardarSolicitudAgrupado()"><i class="fa fa-save"></i>Guardar
                                    Solicitud</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>

<!-- Modal Solicitud de Productos -->
<div class="modal fade" id="modalProcesosMaquina" tabindex="-1">
    <div class="modal-dialog" style="max-width: 60%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ots en Maquinaria <span class="text-info" id="texto-maquina-proceso"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="table_procesos_maquina"></div>
            </div>
            <div class="modal-footer">
                {{-- <button class="btn btn-primary" id="btnGuardarSolicitud">Guardar Solicitud</button> --}}
                <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>

<!-- Modal Solicitud de Productos -->
<div class="modal fade" id="modalEdicionProceso" tabindex="-1">
    <div class="modal-dialog" style="max-width: 40%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edicion de proceso <span class="text-info" id="texto-maquina-proceso"></span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formulario-edicion-proceso">
                    <input type="hidden" name="maquina_id_proceso" id="maquina_id_proceso">
                    <input type="hidden" name="producto_id_proceso" id="producto_id_proceso">
                    <input type="hidden" name="tipo_proceso_id_proceso" id="tipo_proceso_id_proceso">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Fecha Inicio</label>
                            <input type="datetime-local" id="fecha_ini_proceso" name="fecha_ini_proceso"
                                class="form-control" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Tiempo (m)</label>
                            <input type="number" id="tiempo_proceso" name="tiempo_proceso" class="form-control"
                                onkeyup="cambioFechaFinProceso()">
                        </div>
                        <div class="col-md-4">
                            <label>Fecha Fin</label>
                            <input type="datetime-local" id="fecha_fin_proceso" name="fecha_fin_proceso"
                                class="form-control" readonly>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-success w-100 btn-sm" onclick="guardaEdicionProceso()"><i
                                class="fa fa-save"></i>Guardar</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{-- <button class="btn btn-primary" id="btnGuardarSolicitud">Guardar Solicitud</button> --}}
                <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalAgregarProductoAlProceso" tabindex="-1">
    <div class="modal-dialog" style="max-width: 40%">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formulario de agregacion de producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formularioAgregacionProductoProceso">
                    <input type="hidden" name="maquina_idagregacion_proceso" id="maquina_idagregacion_proceso">
                    <input type="hidden" name="tipo_proceso_idagregacion_proceso"
                        id="tipo_proceso_idagregacion_proceso">
                    <div class="row">
                        <div class="col-md-3">
                            <label>Producto</label>
                            <select class="form-select form-select-sm" name="solicitud_id_agregacion_proceso"
                                id="solicitud_id_agregacion_proceso" required></select>
                        </div>
                        <div class="col-md-3">
                            <label>Fecha Inicio</label>
                            <input type="datetime-local" id="fecha_ini_agregacion_proceso"
                                name="fecha_ini_agregacion_proceso" class="form-control form-control-sm" readonly>
                        </div>
                        <div class="col-md-3">
                            <label>Temperatura</label>
                            <input type="number" id="temperatura_agregacion_proceso"
                                name="temperatura_agregacion_proceso" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-3">
                            <label>PH</label>
                            <input type="number" id="ph_agregacion_proceso" name="ph_agregacion_proceso"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-md-4">
                            <label>RB</label>
                            <input type="number" id="rb_agregacion_proceso" name="rb_agregacion_proceso"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-md-8">
                            <label>Descripcion</label>
                            <input type="text" id="descripcion_agregacion_proceso" name="descripcion_agregacion_proceso"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                </form>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-sm w-100 btn-success"
                            onclick="agregarProductoProcesoNuevo()"><i class="fa fa-save"></i>Agregar Producto</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>


@stop

@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>

        const IDS_SOLO_AGUA = @json(config('configuracion.ids_solo_agua'));
        const IDS_SIN_AGUA = @json(config('configuracion.ids_sin_agua'));
        const IDS_AVECES = @json(config('configuracion.ids_aveces_producto_aveses_no'));

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            ajaxListado();
            actualizarTemporizadores(); // iniciar temporizador
            ajaxListadoMaquinas();

            $('#facturas_seleccionadas, #producto_id_solicitud').select2()

            $(document).on('change', '.order_trabajo_id', function () {
                let fila = $(this).closest('[data-repeater-item]');
                let ot_id = $(this).val();
                let productoSelect = fila.find('.producto_id');

                productoSelect.html('<option value="">Cargando productos...</option>');

                if (!ot_id) {
                    productoSelect.html('<option value="">Seleccione producto...</option>');
                    return;
                }

                let urlProductos = "{{ route('procesos.productosAprobadosPorOT', ['ot_id' => ':ot_id']) }}".replace(':ot_id', ot_id);
                $.get(urlProductos, function (productos) {
                    productoSelect.empty().append('<option value="">Seleccione producto...</option>');
                    productos.forEach(p => productoSelect.append(`<option value="${p.id}">${p.nombre}</option>`));
                });
            });

            // Guardar procesos
            $('#guardarProcesosBtn').click(function () {
                let filas = $('#kt_docs_repeater_advanced [data-repeater-item]').filter(':visible');
                let errores = false;

                filas.each(function () {
                    let fila = $(this);
                    let datos = {
                        order_trabajo_id: fila.find('.order_trabajo_id').val(),
                        producto_id: fila.find('.producto_id').val(),
                        tipo_proceso_id: fila.find('.tipo_proceso_id').val(),
                        fecha_ingreso: fila.find('.fecha_ingreso').val(),
                        fecha_salida: fila.find('.fecha_salida').val(),
                        tiempo: fila.find('.tiempo').val(),
                        temperatura: fila.find('.temperatura').val(),
                        ph: fila.find('.ph').val(),
                        rb: fila.find('.rb').val(),
                        descripcion: fila.find('.descripcion').val(),
                        maquinaria_id: $('#maquinaria_id').val(),
                        estado: 'PENDIENTE'
                    };

                    if (!datos.order_trabajo_id || !datos.producto_id || !datos.tipo_proceso_id || !datos.fecha_ingreso) {
                        errores = true;
                        return false;
                    }

                    $.ajax({
                        url: "{{ route('procesos.guardarListado') }}",
                        method: "POST",
                        data: datos,
                        async: false,
                        success: function (res) {
                            if (!res.estado) Swal.fire('Error', res.mensaje, 'error');
                        },
                        error: function (xhr) { console.error(xhr.responseJSON); }
                    });
                });

                if (errores) {
                    Swal.fire('Error', 'Complete todos los campos obligatorios', 'warning');
                } else {
                    Swal.fire('OK', 'Procesos guardados correctamente', 'success');
                    $('#modalLavanderia').modal('hide');
                    recargarListado();
                }
            });

            // function recargarListado() {
            //     $.get("{{ route('procesos.ajaxListado') }}", function (res) {
            //         if (res.estado) $('#table_listado').html(res.data.listado);
            //         else $('#table_listado').html('<p class="text-danger text-center">Error al cargar los procesos</p>');
            //     });
            // }

        });

        $(document).on('change', '.factura_id', function () {
            let fila = $(this).closest('[data-repeater-item]');
            let factura_id = $(this).val();
            let otSelect = fila.find('.order_trabajo_id');
            otSelect.html('<option value="">Cargando OTs...</option>');

            if (!factura_id) {
                otSelect.html('<option value="">Seleccione OT...</option>');
                fila.find('.producto_id').html('<option value="">Seleccione producto...</option>');
                return;
            }

            // 🚀 Ruta correcta con query string
            let url = "{{ route('procesos.otsPorFactura') }}?factura_id=" + factura_id;

            $.get(url, function (data) {
                otSelect.empty().append('<option value="">Seleccione OT...</option>');
                if (Array.isArray(data)) {
                    data.forEach(ot => {
                        otSelect.append(`<option value="${ot.id}">OT ${ot.nro_ot} - Peso: ${ot.peso_total}</option>`);
                    });
                }
                fila.find('.producto_id').html('<option value="">Seleccione producto...</option>');
            });

        });
        // Cuando se selecciona OT en el modal
        $('#select-ot').on('change', function () {
            const ot_id = $(this).val();
            const productoSelect = $('#select-producto');

            productoSelect.html('<option value="">Cargando productos...</option>');

            if (!ot_id) {
                productoSelect.html('<option value="">Seleccione producto...</option>');
                return;
            }

            let url = '/procesos/productos-aprobados/' + ot_id;
            $.get(url, function (productos) {
                productoSelect.empty().append('<option value="">Seleccione producto...</option>');
                productos.forEach(p => {
                    productoSelect.append(`<option value="${p.id}">${p.nombre}</option>`);
                });
            });
        });

        $('#select-factura').on('change', function () {
            const factura_id = $(this).val();
            const otSelect = $('#select-ot');
            otSelect.html('<option value="">Cargando OTs...</option>');

            if (!factura_id) {
                otSelect.html('<option value="">Seleccione OT...</option>');
                $('#select-producto').html('<option value="">Seleccione producto...</option>');
                return;
            }

            $.get('/procesos/ots-por-factura', { factura_id }, function (data) {
                otSelect.empty().append('<option value="">Seleccione OT...</option>');
                data.forEach(ot => {
                    otSelect.append(`<option value="${ot.id}">OT ${ot.nro_ot} - Peso: ${ot.peso_total}</option>`);
                });
                $('#select-producto').html('<option value="">Seleccione producto...</option>');
            });
        });

        function ajaxListadoMaquinas() {
            $.ajax({
                url: "{{ route('procesos.ajaxListadoMaquinas') }}",
                method: "POST",
                data: {},
                success: function (res) {
                    if (res.estado) {
                        $('#bloque-mquinas').html(res.data.listado);
                    }
                }
            });
        }

        function ajaxListado() {
            $.ajax({
                url: "{{ route('procesos.ajaxListado') }}",
                method: "POST",
                data: {},
                success: function (res) {
                    if (res.estado) {
                        $('#table_listado').html(res.data.listado);
                    }
                }
            });
        }

        function cargarOTs(selectId) {
            $.ajax({
                url: "{{ route('procesos.listaOTs') }}",
                type: "GET",
                success: function (data) {
                    let select = $("#" + selectId);
                    select.empty();
                    select.append('<option value="">Seleccione OT...</option>');

                    data.forEach(item => {
                        select.append(
                            `<option value="${item.id}">OT ${item.nro_ot}</option>`
                        );
                    });
                },
                error: function (err) {
                    console.error("Error cargando OTs", err);
                    Swal.fire('Error', 'No se pudieron cargar las OTs', 'error');
                }
            });
        }


        function guardarLavanderia() {
            let datos = {
                id: $('#id').val(),
                order_trabajo_id: $('#order_trabajo_id').val(),
                producto_id: $('#producto_id').val(),
                maquinaria_id: $('#maquinaria_id').val(),
                tipo_proceso_id: $('#tipo_proceso_id').val(),
                fecha_ingreso: $('#fecha_ingreso').val(),
                fecha_salida: $('#fecha_salida').val(),
                tiempo: $('#tiempo').val(),
                temperatura: $('#temperatura').val(),
                ph: $('#ph').val(),
                rb: $('#rb').val(),
                descripcion: $('#descripcion').val(),
                estado: $('#estado').val()
            };

            console.log("Datos enviados:", datos); // <-- depuración rápida

            // Validación mínima antes de enviar
            if (!datos.order_trabajo_id) {
                Swal.fire('Error', 'Debe seleccionar una Orden de Trabajo', 'error');
                return;
            }
            // if (!datos.producto_id) {
            //     Swal.fire('Error', 'Debe seleccionar un producto', 'error');
            //     return;
            // }
            if (!datos.tipo_proceso_id) {
                Swal.fire('Error', 'Debe seleccionar un tipo de proceso', 'error');
                return;
            }
            if (!datos.maquinaria_id) {
                Swal.fire('Error', 'Maquinaria no seleccionada', 'error');
                return;
            }
            if (!datos.fecha_ingreso) {
                Swal.fire('Error', 'Debe indicar la fecha de ingreso', 'error');
                return;
            }

            $.ajax({
                url: "{{ route('procesos.guardarListado') }}",
                method: "POST",
                data: datos,
                success: function (res) {
                    if (res.estado) {
                        Swal.fire('Correcto', res.mensaje, 'success');
                        $('#modalLavanderia').modal('hide');
                        ajaxListado();
                    } else {
                        Swal.fire('Error', res.mensaje, 'error');
                    }
                },
                error: function (xhr) {
                    console.log("Error detalle:", xhr.responseJSON);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let mensaje = '';
                        for (let key in errors) mensaje += errors[key] + '\n';
                        Swal.fire('Error de validación', mensaje, 'error');
                    } else {
                        Swal.fire('Error', 'Ocurrió un error en el servidor', 'error');
                    }
                }
            });
        }


        // ================== TEMPORIZADOR ==================
        function actualizarTemporizadores() {
            setInterval(function () {
                $('.countdown').each(function () {
                    let fin = $(this).data('fin');
                    if (!fin) return;

                    let now = new Date().getTime();
                    let end = new Date(fin).getTime();
                    let diff = end - now;

                    if (diff <= 0) {
                        $(this).html("Finalizado");

                        // actualizar estados de procesos y máquinas
                        $.post("{{ route('procesos.actualizarEstados') }}");
                        return;
                    }

                    let m = Math.floor(diff / 1000 / 60);
                    let s = Math.floor((diff / 1000) % 60);
                    $(this).html(m + "m " + s + "s");
                });
            }, 1000);
        }

        // function cargarProductosLavanderia(ot_id) {
        //     if (!ot_id) return;

        //     // Construir la URL reemplazando :ot_id
        //     let url = "{{ route('procesos.productosAprobadosPorOT', ['ot_id' => ':ot_id']) }}";
        //     url = url.replace(':ot_id', ot_id);

        //     $.get(url, function (data) {
        //         console.log("Productos Lavandería para OT", ot_id, data);
        //         let select = $("#producto_id_lavanderia");
        //         select.empty();
        //         select.append('<option value="">Seleccione...</option>');
        //         data.forEach(item => select.append(`<option value="${item.id}">${item.nombre}</option>`));
        //     });
        // }

        function cargarProductosPorOT(ot_id, fila) {
            let productoSelect = fila.find('.producto_id'); // fila específica del repeater

            if (!ot_id) {
                productoSelect.html('<option value="">Seleccione producto...</option>');
                return;
            }

            let urlProductos = "{{ route('procesos.productosAprobadosPorOT', ['ot_id' => ':ot_id']) }}";
            urlProductos = urlProductos.replace(':ot_id', ot_id);

            $.get(urlProductos, function (productos) {
                productoSelect.empty().append('<option value="">Seleccione producto...</option>');
                productos.forEach(p => {
                    productoSelect.append(`<option value="${p.id}">${p.nombre}</option>`);
                });
            });
        }




        function inicializarRepeater() {
            if (!$('#kt_docs_repeater_advanced').data('repeater')) {
                $('#kt_docs_repeater_advanced').repeater({
                    initEmpty: false,
                    show: function () { $(this).slideDown(); },
                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                });
            }
        }

        function cargarTiposProcesoEnRepeater() {
            $.get("{{ route('procesos.listaTiposProceso') }}", function (data) {
                $('.tipo_proceso_id').each(function () {
                    let select = $(this);
                    select.empty().append('<option value="">Seleccione...</option>');
                    data.forEach(tp => {
                        select.append(`<option value="${tp.id}">${tp.nombre}</option>`);
                    });
                });
            });
        }
        function cargarTiposProcesoEnFila(fila) {
            let tipoSelect = fila.find('.tipo_proceso_id');
            $.get("{{ route('procesos.listaTiposProceso') }}", function (tipos) {
                tipoSelect.empty().append('<option value="">Seleccione...</option>');
                tipos.forEach(tp => tipoSelect.append(`<option value="${tp.id}">${tp.nombre}</option>`));
            });
        }

        //ororo




        //procesos

        let listadoProcesos = [];

        $('#agregarAlListado').click(function () {
            agregarProcesoAlListado();
        });

        function cargarFacturas() {
            $.ajax({
                url: "{{ route('factura.estadoNull') }}",
                type: 'GET',
                dataType: 'json',
                success: function (facturas) {
                    let options = '<option value="">Seleccione factura...</option>';
                    facturas.forEach(f => {
                        options += `<option value="${f.id}">${f.numero_factura} </option>`;
                    });
                    $('#factura_id').html(options);
                },
                error: function (err) {
                    console.error('Error al cargar facturas:', err);
                    alert('No se pudieron cargar las facturas.');
                }
            });
        }

        $('#factura_id').on('change', function () {
            let facturaId = $(this).val();

            if (!facturaId) {
                $('#order_trabajo_id_lavanderia').html('<option value="">Seleccione OT...</option>');
                return;
            }

            $.ajax({
                url: "{{ route('factura.obtenerOTs') }}", // Creamos ruta Laravel
                type: 'GET',
                data: { factura_id: facturaId },
                dataType: 'json',
                success: function (ots) {
                    let options = '<option value="">Seleccione OT...</option>';
                    ots.forEach(ot => {
                        options += `<option value="${ot.id}">OT: ${ot.nro_ot} - Prendas: ${ot.cantidad}</option>`;
                    });
                    $('#order_trabajo_id_lavanderia').html(options);
                },
                error: function (err) {
                    console.error('Error al cargar OTs:', err);
                    alert('No se pudieron cargar las OTs.');
                }
            });
        });



        function modalNuevaLavanderiaConMaquinaria(maquinaria_id) {

            $.ajax({
                url: "{{ route('solicitudes.ajaxProductoSolicitud') }}",
                type: 'POST',
                dataType: 'json',
                success: function (respuesta) {
                    console.log(respuesta);

                    let select = $('#producto_solicitud_aprobado');
                    select.empty();
                    select.append('<option value="">Seleccione producto...</option>');

                    let datos = respuesta.data.solicitudes;

                    datos.forEach(e => {
                        select.append(`<option value="${e.producto_id}">${e.producto.nombre}</option>`)
                    })

                    // Limpiar formulario
                    $('#id').val(0);
                    $('#order_trabajo_id_lavanderia').val('');
                    $('#producto_id_lavanderia').html('<option value="">Seleccione producto...</option>');
                    $('#tipo_proceso_id').val('');
                    $('#fecha_ingreso').val('');
                    $('#fecha_salida').val('');
                    $('#tiempo').val('');
                    $('#temperatura').val('');
                    $('#ph').val('');
                    $('#rb').val('');
                    $('#descripcion').val('');
                    $('#estado').val('PENDIENTE');
                    $('#maquinaria_id').val(maquinaria_id);

                    $('#order_trabajo_id_lavanderia').html('<option value="">Seleccione OT...</option>');
                    $('#producto_id_lavanderia').html('<option value="">Seleccione producto...</option>');
                    // Cargar tipos de proceso
                    cargarTiposProceso();
                    // cargarFacturas();

                    // Mostrar modal
                    $('#modalLavanderia').modal('show');

                },
                error: function (err) {
                    console.error('Error al cargar facturas:', err);
                    alert('No se pudieron cargar las facturas.');
                }
            });

        }

        function cargarTiposProceso() {
            $.get("{{ route('procesos.listaTiposProceso') }}", function (data) {
                let select = $("#tipo_proceso_id");
                select.empty();
                select.append('<option value="">Seleccione...</option>');
                data.forEach(item => select.append(`<option value="${item.id}">${item.nombre}</option>`));
            });
        }

        function obtenerSolcitudProductoAprobado() {
            let orderTrabajoId = $('#order_trabajo_id_lavanderia').val();
            let select = $('#producto_id_lavanderia');
            select.html('<option value="">Cargando productos...</option>');

            if (orderTrabajoId) {
                // {
                //     {
                //         --
                //             $.get("{{ route('factura.obtenerProductosAprobados') }}", { order_trabajo_id: orderTrabajoId }, function (data) {
                //                 select.empty();
                //                 select.append('<option value="">Seleccione producto...</option>');
                //                 data.forEach(p => {
                //                     select.append(`<option value="${p.id}">${p.nombre} - ${p.tipo}</option>`);
                //                 });
                //             }).fail(function (err) {
                //                 console.error('Error al cargar productos:', err);
                //                 select.html('<option value="">Error al cargar productos</option>');
                //             });
                //         --}
                // }

                $.ajax({
                    url: "{{ route('factura.obtenerProductosAprobados') }}", // Creamos ruta Laravel
                    type: 'POST',
                    data: { orderTrabajoId: orderTrabajoId },
                    dataType: 'json',
                    success: function (ots) {

                        console.log("###################################################");
                        console.log(ots);
                        console.log("###################################################");

                        // let options = '<option value="">Seleccione OT...</option>';
                        // ots.forEach(ot => {
                        //     options += `<option value="${ot.id}">OT: ${ot.nro_ot} - Prendas: ${ot.cantidad}</option>`;
                        // });
                        // $('#order_trabajo_id_lavanderia').html(options);
                    },
                    error: function (err) {
                        console.error('Error al cargar OTs:', err);
                        alert('No se pudieron cargar las OTs.');
                    }
                });
            } else {
                select.html('<option value="">Seleccione OT primero</option>');
            }
        }

        function agregarProcesoAlListado() {
            let proceso = {
                order_trabajo_id: $('#order_trabajo_id_lavanderia').val(),
                producto_id: $('#producto_solicitud_aprobado').val(),
                maquinaria_id: $('#maquinaria_id').val(),
                tipo_proceso_id: $('#tipo_proceso_id').val(),
                fecha_ingreso: $('#fecha_ingreso').val(),
                fecha_salida: $('#fecha_salida').val(),
                cantidad: $('#cantidad').val() || null,
                porcentaje: $('#porcentaje').val() || null,
                gr_litro: $('#gr_litro').val() || null,
                tiempo: $('#tiempo').val() || null,
                temperatura: $('#temperatura').val() || null,
                ph: $('#ph').val() || null,
                rb: $('#rb').val() || null,
                descripcion: $('#descripcion').val(),
                estado: 'TRABAJANDO',
                producto_solicitud_aprobado: $('#producto_solicitud_aprobado').val(),
                ordenes_trabajos_solicitudes_aprobados: $('#ordenes_trabajos_solicitudes_aprobados').val(),
                nombre_producto_seleccionado: $('#producto_solicitud_aprobado option:selected').text(),
                nombre_precesso_seleccionado: $('#tipo_proceso_id option:selected').text(),
            };

            // Validaciones básicas
            // if (!proceso.order_trabajo_id || !proceso.producto_id || !proceso.tipo_proceso_id) {
            if (!proceso.producto_id || !proceso.tipo_proceso_id) {
                if (!IDS_SOLO_AGUA.includes(parseInt(proceso.tipo_proceso_id))) {
                    Swal.fire('Error', 'Debe seleccionar OT, producto y tipo de proceso.', 'warning');
                    return;
                }
            }

            listadoProcesos.push(proceso);
            mostrarListadoTemporal();
            limpiarFormularioModal();
        }

        function mostrarListadoTemporal() {
            let tbody = $('#tablaListadoTemporal tbody'); // corregido
            tbody.empty();

            listadoProcesos.forEach((p, index) => {
                tbody.append(`
                                <tr>
                                    <td>${$('#ordenes_trabajos_solicitudes_aprobados option:selected').text()}</td>
                                    <td>${p.nombre_producto_seleccionado}</td>
                                    <td>${p.nombre_precesso_seleccionado}</td>
                                    <td>${p.fecha_ingreso}</td>
                                    <td>${p.fecha_salida}</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="eliminarProceso(${index})">Eliminar</button>
                                    </td>
                                </tr>
                            `);
            });
        }


        function eliminarProceso(index) {
            listadoProcesos.splice(index, 1);
            mostrarListadoTemporal();
        }

        $('#guardarListado').click(function () {
            guardarListadoProcesos();
        });

        function guardarListadoProcesos() {
            if (listadoProcesos.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'No hay procesos para guardar.'
                });
                return;
            }

            $.ajax({
                url: "{{ route('procesos.guardarListado') }}",
                type: 'POST',
                data: { procesos: listadoProcesos },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function (response) {
                    if (response.estado) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.mensaje,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        // Limpiar listado temporal
                        ajaxListado();
                        listadoProcesos = [];
                        mostrarListadoTemporal();
                        ajaxListadoMaquinas();
                        $('#modalLavanderia').modal('hide');
                    } else {
                        alert('No se guardaron los procesos.');
                    }
                },
                error: function (err) {
                    console.error('Error al guardar procesos:', err);
                    alert('Ocurrió un error al guardar los procesos.');
                }
            });
        }



        function limpiarFormularioModal() {
            $('#order_trabajo_id_lavanderia').val('');
            $('#producto_id_lavanderia').val('');
            // $('#tipo_proceso_id').val('');
            $('#tipo_proceso_id').val(null).trigger('change');
            $('#fecha_ingreso').val('');
            $('#fecha_salida').val('');
            $('#tiempo').val('');
            $('#temperatura').val('');
            $('#ph').val('');
            $('#rb').val('');
            $('#descripcion').val('');
        }

        function buscarSolicitudesProducto() {
            let productoId = $('#producto_solicitud_aprobado').val()

            $.ajax({
                url: "{{ route('solicitudes.buscarSolicitudesProducto') }}",
                type: 'POST',
                dataType: 'json',
                data: { productoId: productoId },
                success: function (respuesta) {

                    let select = $('#ordenes_trabajos_solicitudes_aprobados')
                    select.empty();
                    select.append('<option value="">Seleccione solicitud...</option>');

                    let fac = respuesta.data.fac;

                    Object.entries(fac).forEach(([key, value]) => {
                        select.append(
                            `<option value="${key}">${value}</option>`
                        );
                    });

                },
                error: function (err) {
                    console.error('Error al cargar facturas:', err);
                    alert('No se pudieron cargar las facturas.');
                }
            });
        }

        function abrirModalSolicitud() {
            let modal = new bootstrap.Modal(document.getElementById('modalSolicitudProductos'));
            modal.show();
        }

        let productosTemporal = [];
        let productosTemporalAgrupado = [];
        let otSeleccionadasPorFactura = {};

        let editandoCantidad = false;
        let editandoPorcentaje = false;

        $(document).ready(function () {

            // Inicializar Select2
            $('#facturas_seleccionadas, #producto_id_solicitud').select2({
                dropdownParent: $('#modalSolicitudProductos')
            });


            $('#facturas_seleccionadas').on('change', function () {
                const facturas = $(this).val() || [];
                const container = $('#ots_por_factura_container');
                container.html('');
                otSeleccionadasPorFactura = {};

                facturas.forEach(function (facturaId) {
                    const nroFactura = $('#facturas_seleccionadas option[value="' + facturaId + '"]').data('nro');

                    const div = $('<div class="mb-2"><strong>Factura ' + nroFactura + ':</strong></div>');
                    container.append(div);

                    let url = "{{ route('solicitudes.otsPorFactura', ':id') }}".replace(':id', facturaId);

                    $.get(url, function (data) {
                        data.forEach(function (ot) {

                            const wrapper = $('<div class="form-check form-check-inline"></div>');

                            const input = $('<input class="form-check-input" type="checkbox">')
                                // .attr('data-ot-id', ot.ids[0])
                                .attr('data-ot-id', ot.ids)
                                .attr('data-nro-ot', ot.nro_ot)
                                .attr('data-peso', ot.peso_total)
                                .attr('data-factura-id', facturaId);

                            if (ot.disabled) {
                                input.prop('disabled', true);
                                input.addClass('is-invalid');
                            }

                            const label = $('<label class="form-check-label"></label>').text('OT ' + ot.nro_ot + ' (Peso: ' + ot.peso_total + ')');

                            wrapper.append(input).append(label);
                            div.append(wrapper);
                        });
                    });
                });
            });


            $('#porcentaje_solicitado').on('input', function () {
                if (editandoCantidad) return;
                editandoPorcentaje = true;
                calcularCantidadDesdePorcentaje();
                editandoPorcentaje = false;
            });

            $('#cantidad_solicitada').on('input', function () {
                if (editandoPorcentaje) return;
                editandoCantidad = true;
                calcularPorcentajeDesdeCantidad();
                editandoCantidad = false;
            });




            $('#porcentaje_solicitado_agrupado').on('input', function () {
                // if (editandoCantidad) return;
                // editandoPorcentaje = true;
                calcularCantidadDesdePorcentajeAgrupado();
                // editandoPorcentaje = false;
            });

            $('#cantidad_solicitada_agrupado').on('input', function () {
                // if (editandoPorcentaje) return;
                // editandoCantidad = true;
                calcularPorcentajeDesdeCantidadAgrupado();
                // editandoCantidad = false;
            });

            $(document).on('change', '#ots_por_factura_container input[type="checkbox"]', function () {
                calcularCantidadDesdePorcentaje();
            });


            $('#btnAgregarProducto').on('click', function () {

                const productoId = $('#producto_id_solicitud').val();
                const productoNombre = $('#producto_id_solicitud option:selected').text();

                const cantidad = parseFloat($('#cantidad_solicitada').val()) || 0;
                const porcentaje = parseFloat($('#porcentaje_solicitado').val()) || 0;

                if (!productoId || cantidad <= 0 || porcentaje <= 0) {
                    Swal.fire('Error', 'Ingrese un producto, cantidad o porcentaje válido', 'warning');
                    return;
                }

                let facturas = [];

                $('#ots_por_factura_container > div').each(function () {

                    const facturaLabel = $(this).find('strong').text();
                    const nroFactura = parseInt(facturaLabel.replace('Factura ', ''));
                    const ots = [];
                    let facturaId = null;

                    $(this).find('input[type="checkbox"]:checked').each(function () {

                        const otId = $(this).data('ot-id');
                        const nroOt = $(this).data('nro-ot');

                        if (!facturaId) {
                            facturaId = $(this).data('factura-id');
                        }

                        if (otId && nroOt) {
                            const otIdsArray = otId.toString().split(',');
                            otIdsArray.forEach(id => {
                                ots.push({
                                    id: parseInt(id.trim()),
                                    nro_ot: parseInt(nroOt)
                                });
                            });

                            // ots.push({
                            //     id: parseInt(otId),
                            //     nro_ot: parseInt(nroOt)
                            // });
                        }
                    });

                    if (ots.length > 0 && facturaId) {
                        facturas.push({
                            factura_id: parseInt(facturaId),
                            nro_factura: nroFactura,
                            ots: ots
                        });
                    }
                });

                if (facturas.length === 0) {
                    Swal.fire('Error', 'Seleccione al menos una OT', 'warning');
                    return;
                }

                productosTemporal.push({
                    producto_id: parseInt(productoId),
                    producto_nombre: productoNombre,
                    porcentaje: porcentaje,
                    cantidad: cantidad,
                    estado: "EN ESPERA",
                    facturas: facturas
                });

                // RESET
                $('#producto_id_solicitud').val('').trigger('change');
                $('#porcentaje_solicitado').val('');
                $('#cantidad_solicitada').val('');
                $('#facturas_seleccionadas').val([]).trigger('change');
                $('#ots_por_factura_container').html('');

                actualizarTablaTemporal();
            });

            $('#btnGuardarSolicitud').on('click', function () {


                let focalizado = $('#check_focalizado').is(':checked');

                // CASO FOCALIZADO
                if (focalizado) {

                    console.log("⚡ Solicitud focalizado detectada");

                    guardarSolicitudFocalizado();

                    return;
                }

                if (productosTemporal.length === 0) {
                    Swal.fire('Error', 'Agregue al menos un producto', 'warning');
                    return;
                }

                const solicitudesPayload = productosTemporal.map(p => ({
                    producto_id: p.producto_id,
                    porcentaje: p.porcentaje,
                    cantidad: p.cantidad,
                    estado: p.estado,
                    facturas: p.facturas.map(f => ({
                        factura_id: f.factura_id,
                        nro_factura: f.nro_factura,
                        ots: f.ots.map(ot => ot.id)
                    }))
                }));

                $.post("{{ route('solicitudes.store') }}", {
                    _token: "{{ csrf_token() }}",
                    solicitudes: solicitudesPayload
                }, function () {
                    Swal.fire('OK', 'Solicitudes guardadas correctamente', 'success');
                    $('#modalSolicitudProductos').modal('hide');
                    productosTemporal = [];
                    actualizarTablaTemporal();
                }).fail(function () {
                    Swal.fire('Error', 'Error al guardar la solicitud', 'error');
                });
            });

        });


        function agregarAlListadoSolicitudAgrupado() {

            const productoId = $('#producto_id_solicitud_agrupado').val();
            const productoNombre = $('#producto_id_solicitud_agrupado option:selected').text();
            const cantidad = parseFloat($('#cantidad_solicitada_agrupado').val()) || 0;
            const porcentaje = parseFloat($('#porcentaje_solicitado_agrupado').val()) || 0;
            const solicitud_agrupado_id = $('#solicitud_agrupado_id').val();
            const solicitud_agrupado_id_text = $('#solicitud_agrupado_id option:selected').text();

            console.log(
                (!productoId || cantidad <= 0 || porcentaje <= 0),
                productoId,
                cantidad,
                porcentaje,
                (!productoId),
                (cantidad <= 0),
                (porcentaje <= 0)
            );

            if (!productoId || cantidad <= 0 || porcentaje <= 0) {
                Swal.fire('Error', 'Ingrese un producto, cantidad o porcentaje válido', 'warning');
                return;
            }

            productosTemporalAgrupado.push({
                producto_id: parseInt(productoId),
                producto_nombre: productoNombre,
                porcentaje: porcentaje,
                cantidad: cantidad,
                estado: "EN PROCESO",
                solicitud: solicitud_agrupado_id,
                solicitud_agrupado_id_text: solicitud_agrupado_id_text
            });

            // // RESET
            $('#producto_id_solicitud_agrupado').val('').trigger('change');
            $('#porcentaje_solicitado_agrupado').val(0);
            $('#cantidad_solicitada_agrupado').val(0);
            $('#peso_total_ot_agrupado').val(0);
            $('#solicitud_agrupado_id').val('');

            actualizarTablaTemporalAgrupado();

        }


        function calcularPesoTotalOT() {
            let total = 0;
            $('#ots_por_factura_container input[type="checkbox"]:checked').each(function () {
                total += parseFloat($(this).data('peso')) || 0;
            });

            $('#peso_total_ot').val(total.toFixed(2));

            return total;
        }

        function calcularCantidadDesdePorcentaje() {
            const pesoTotal = calcularPesoTotalOT();
            const porcentaje = parseFloat($('#porcentaje_solicitado').val()) || 0;
            if (pesoTotal <= 0) return;

            const cantidad = ((pesoTotal * porcentaje) / 100) * 1000;
            $('#cantidad_solicitada').val(cantidad.toFixed(2));
        }

        function calcularPorcentajeDesdeCantidad() {
            const pesoTotal = calcularPesoTotalOT();
            const cantidad = parseFloat($('#cantidad_solicitada').val()) || 0;
            if (pesoTotal <= 0) return;

            //const porcentaje = (cantidad * 1000) / pesoTotal;
            const porcentaje = (cantidad / (pesoTotal * 1000)) * 100;
            $('#porcentaje_solicitado').val(porcentaje.toFixed(2));
        }

        function calcularPorcentajeDesdeCantidadAgrupado() {
            const pesoTotal = $('#peso_total_ot_agrupado').val();
            const cantidad = parseFloat($('#cantidad_solicitada_agrupado').val()) || 0;
            if (pesoTotal <= 0) return;

            //const porcentaje = (cantidad * 1000) / pesoTotal;
            const porcentaje = (cantidad / (pesoTotal * 1000)) * 100;
            $('#porcentaje_solicitado_agrupado').val(porcentaje.toFixed(2));
        }

        function calcularCantidadDesdePorcentajeAgrupado() {
            const pesoTotal = $('#peso_total_ot_agrupado').val();
            const porcentaje = parseFloat($('#porcentaje_solicitado_agrupado').val()) || 0;
            if (pesoTotal <= 0) return;

            const cantidad = ((pesoTotal * porcentaje) / 100) * 1000;
            $('#cantidad_solicitada_agrupado').val(cantidad.toFixed(2));
        }

        function actualizarTablaTemporal() {
            const tbody = $('#tabla_solicitud_temporal tbody');
            tbody.empty();

            productosTemporal.forEach(function (item, index) {
                tbody.append(`
                                                                <tr>
                                                                    <td>${item.producto_nombre}</td>
                                                                    <td>${item.facturas.map(f => 'Factura ' + f.nro_factura + ' → [' + f.ots.map(o => o.nro_ot).join(', ') + ']').join(' | ')}</td>
                                                                    <td>${item.porcentaje}%</td>
                                                                    <td>${item.cantidad.toFixed(2)}</td>
                                                                    <td>
                                                                        <button class="btn btn-danger btn-sm" onclick="eliminarProducto(${index})">
                                                                            Eliminar
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            `);
            });
        }

        function actualizarTablaTemporalAgrupado() {
            const tbody = $('#tabla_solicitud_temporal_agrupado tbody');
            tbody.empty();

            productosTemporalAgrupado
                .forEach(function (item, index) {
                    tbody.append(`
                                                                        <tr>
                                                                            <td>${item.producto_nombre}</td>
                                                                            <td>${item.solicitud_agrupado_id_text}</td>
                                                                            <td>${item.porcentaje}%</td>
                                                                            <td>${item.cantidad.toFixed(2)}</td>
                                                                            <td>
                                                                                <button class="btn btn-danger btn-sm" onclick="eliminarProductoAgrupado(${index})">
                                                                                    Eliminar
                                                                                </button>
                                                                            </td>
                                                                        </tr>
                                                                    `);
                });
        }

        function eliminarProducto(index) {
            productosTemporal.splice(index, 1);
            actualizarTablaTemporal();
        }

        function eliminarProductoAgrupado(index) {
            productosTemporalAgrupado.splice(index, 1);
            actualizarTablaTemporalAgrupado();
        }

        function verificaTipoProceso() {

            let tipo_proceso_id = parseInt($('#tipo_proceso_id').val())

            if (IDS_SOLO_AGUA.includes(tipo_proceso_id)) {
                $.ajax({
                    url: "{{ route('procesos.buscarSolicitudesProductoSoloAgua') }}",
                    type: 'POST',
                    dataType: 'json',
                    // data: { productoId: productoId },
                    success: function (respuesta) {

                        console.log(respuesta);

                        let select = $('#ordenes_trabajos_solicitudes_aprobados')
                        select.empty();
                        select.append('<option value="">Seleccione solicitud...</option>');

                        let fac = respuesta.data.fac;

                        Object.entries(fac).forEach(([key, value]) => {
                            select.append(
                                `<option value="${key}">${value}</option>`
                            );
                        });

                    },
                    error: function (err) {
                        console.error('Error al cargar facturas:', err);
                        alert('No se pudieron cargar las facturas.');
                    }
                });
            } else if (IDS_SIN_AGUA.includes(tipo_proceso_id)) {
                console.log("👉 SIN AGUA");
            } else if (IDS_AVECES.includes(tipo_proceso_id)) {
                console.log("👉 AVECES PRODUCTO");
            } else {
                console.log("SI O SI PIDE");
            }
        }

        function verProcesoEnMarchaMaquina(maquina) {

            $.ajax({
                url: "{{ route('procesos.verProcesoEnMarchaMaquina') }}",
                type: 'POST',
                dataType: 'json',
                data: { maquina: maquina },
                success: function (respuesta) {

                    if (respuesta.estado) {
                        $('#texto-maquina-proceso').text(respuesta.data.dato)
                        $('#table_procesos_maquina').html(respuesta.data.listado)
                        $('#modalProcesosMaquina').modal('show')
                    } else {

                    }

                },
                error: function (err) {
                    console.error('Error al cargar facturas:', err);
                    // alert('No se pudieron cargar las facturas.');
                }
            });

        }

        function calcularTiempoFinal() {
            let fechaIni = $('#fecha_ingreso').val()
            let tiempo = $('#tiempo').val()
            if (!fechaIni || !tiempo) return;
            let fecha = new Date(fechaIni);
            fecha.setMinutes(fecha.getMinutes() + parseInt(tiempo));
            let yyyy = fecha.getFullYear();
            let mm = String(fecha.getMonth() + 1).padStart(2, '0');
            let dd = String(fecha.getDate()).padStart(2, '0');
            let hh = String(fecha.getHours()).padStart(2, '0');
            let min = String(fecha.getMinutes()).padStart(2, '0');
            let fechaFinal = `${yyyy}-${mm}-${dd}T${hh}:${min}`;
            $('#fecha_salida').val(fechaFinal);
        }

        function finalizarProceso(maquina) {

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
                        url: "{{ route('procesos.finalizarProceso') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: { maquina: maquina },
                        success: function (respuesta) {

                            if (respuesta.estado) {
                                ajaxListadoMaquinas();
                                $('#modalProcesosMaquina').modal('hide');
                                Swal.fire({
                                    title: "FINALIZADO!",
                                    text: "El proceos finalizo con exito.",
                                    icon: "success"
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: JSON.stringify(respuesta),
                                    icon: "warning"
                                });
                            }
                        },
                        error: function (err) {
                            console.error('Error al cargar facturas:', err);
                            // alert('No se pudieron cargar las facturas.');
                        }
                    });

                }
            });

        }

        function sacarSolicitudesAgrupados() {
            $.ajax({
                url: "{{ route('procesos.sacarSolicitudesAgrupados') }}",
                method: "POST",
                // data: datos,
                success: function (res) {
                    if (res.estado) {

                        let select = $('#solicitud_agrupado_id');
                        select.empty()
                        select.append(`<option value="">SELECCIONE UN AGRUPADO</option>`);

                        let datosRecibidos = res.data.fac;

                        Object.entries(datosRecibidos).forEach(([key, value]) => {
                            select.append(
                                `<option value="${key}">${value}</option>`
                            );
                        });
                    } else {
                        Swal.fire('Error', res.mensaje, 'error');
                    }
                },
                error: function (xhr) {
                    console.log("Error detalle:", xhr.responseJSON);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let mensaje = '';
                        for (let key in errors) mensaje += errors[key] + '\n';
                        Swal.fire('Error de validación', mensaje, 'error');
                    } else {
                        Swal.fire('Error', 'Ocurrió un error en el servidor', 'error');
                    }
                }
            });
        }

        function sacarPesoTotalSolicitudAgrupado() {

            let dato = $('#solicitud_agrupado_id').val()

            $.ajax({
                url: "{{ route('procesos.sacarPesoTotalSolicitudAgrupado') }}",
                method: "POST",
                data: { dato: dato },
                success: function (res) {
                    if (res.estado) {

                        $('#peso_total_ot_agrupado').val(res.data.peso_total)
                    } else {
                        Swal.fire('Error', res.mensaje, 'error');
                    }
                },
                error: function (xhr) {
                    console.log("Error detalle:", xhr.responseJSON);
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let mensaje = '';
                        for (let key in errors) mensaje += errors[key] + '\n';
                        Swal.fire('Error de validación', mensaje, 'error');
                    } else {
                        Swal.fire('Error', 'Ocurrió un error en el servidor', 'error');
                    }
                }
            });

        }

        function btnGuardarSolicitudAgrupado() {

            if (productosTemporalAgrupado.length === 0) {
                Swal.fire('Error', 'Agregue al menos un producto', 'warning');
                return;
            }

            const solicitudesPayload = productosTemporalAgrupado.map(p => ({
                producto_id: p.producto_id,
                porcentaje: p.porcentaje,
                cantidad: p.cantidad,
                estado: p.estado,
                solicitud: p.solicitud
            }));

            $.post("{{ route('procesos.GuardarSolicitudAgrupado') }}", {
                _token: "{{ csrf_token() }}",
                solicitudes: solicitudesPayload
            }, function (respuesta) {

                if (respuesta.estado) {
                    productosTemporalAgrupado = [];
                    actualizarTablaTemporalAgrupado();
                    $('#modalSolicitudProductos').modal('hide');
                    Swal.fire('Exito', 'Solicitudes guardadas correctamente', 'success');
                } else {
                    Swal.fire('Error', 'Ocurrio algo al guardar la solicitud', 'warning');
                }
            }).fail(function () {
                // Swal.fire('Error', 'Error al guardar la solicitud', 'error');
            });

        }

        function editarTiempoProceso(proceos) {
            $('#fecha_ini_proceso').val(proceos.fecha_ingreso)
            $('#tiempo_proceso').val(proceos.tiempo)
            $('#fecha_fin_proceso').val(proceos.fecha_salida)

            $('#maquina_id_proceso').val(proceos.maquinaria_id)
            $('#producto_id_proceso').val(proceos.producto_id)
            $('#tipo_proceso_id_proceso').val(proceos.tipo_proceso_id)

            $('#modalEdicionProceso').modal('show')
        }

        function cambioFechaFinProceso() {
            let fechaIni = $('#fecha_ini_proceso').val()
            let tiempo = $('#tiempo_proceso').val()
            if (!fechaIni || !tiempo) return;
            let fecha = new Date(fechaIni);
            fecha.setMinutes(fecha.getMinutes() + parseInt(tiempo));
            let yyyy = fecha.getFullYear();
            let mm = String(fecha.getMonth() + 1).padStart(2, '0');
            let dd = String(fecha.getDate()).padStart(2, '0');
            let hh = String(fecha.getHours()).padStart(2, '0');
            let min = String(fecha.getMinutes()).padStart(2, '0');
            let fechaFinal = `${yyyy}-${mm}-${dd}T${hh}:${min}`;
            $('#fecha_fin_proceso').val(fechaFinal);
        }

        function guardaEdicionProceso() {

            Swal.fire({
                title: "Esta seguro de editar el proceso?",
                text: "No podras revertir eso!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Editar!"
            }).then((result) => {
                if (result.isConfirmed) {

                    let datos = $('#formulario-edicion-proceso').serializeArray();

                    $.ajax({
                        url: "{{ route('procesos.guardaEdicionProceso') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: datos,
                        success: function (respuesta) {

                            if (respuesta.estado) {
                                $('#modalEdicionProceso').modal('hide');
                                $('#modalProcesosMaquina').modal('hide');
                                Swal.fire({
                                    title: "FINALIZADO!",
                                    text: "El proceos actualizado con exito.",
                                    icon: "success"
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: JSON.stringify(respuesta),
                                    icon: "warning"
                                });
                            }
                        },
                        error: function (err) {
                            console.error('Error al cargar facturas:', err);
                            // alert('No se pudieron cargar las facturas.');
                        }
                    });

                }
            });

        }

        function agregarProductoAlProceso(maquina, tipo_proceso) {

            $.ajax({
                url: "{{ route('procesos.agregarProductoAlProceso') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    maquina: maquina,
                    tipo_proceso: tipo_proceso
                },
                success: function (respuesta) {

                    if (respuesta.estado) {
                        let select = $('#solicitud_id_agregacion_proceso')
                        select.empty();
                        let solicitudes = respuesta.data.solicitudes;
                        solicitudes.forEach(e => {
                            let g = e.estado == "UTILIZADO" ? 'disabled' : '';
                            select.append('<option ' + g + ' value="' + e.id + '">' + e.producto?.nombre + '</option>');
                        });
                        let now = new Date();
                        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
                        let fechaHora = now.toISOString().slice(0, 16);
                        $('#fecha_ini_agregacion_proceso').val(fechaHora);
                        $('#tipo_proceso_idagregacion_proceso').val(tipo_proceso);
                        $('#maquina_idagregacion_proceso').val(maquina);
                        $('#modalAgregarProductoAlProceso').modal('show')
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: JSON.stringify(respuesta),
                            icon: "warning"
                        });
                    }
                },
                error: function (err) {
                    console.error('Error al cargar facturas:', err);
                    // alert('No se pudieron cargar las facturas.');
                }
            });

        }

        function agregarProductoProcesoNuevo() {

            if ($("#formularioAgregacionProductoProceso")[0].checkValidity()) {
                Swal.fire({
                    title: "Esta seguro de agergar el producto al proceso?",
                    text: "No podras revertir eso!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, Agregar!"
                }).then((result) => {
                    if (result.isConfirmed) {

                        let datos = $('#formularioAgregacionProductoProceso').serializeArray();

                        $.ajax({
                            url: "{{ route('procesos.agregarProductoProcesoNuevo') }}",
                            type: 'POST',
                            dataType: 'json',
                            data: datos,
                            success: function (respuesta) {

                                if (respuesta.estado) {
                                    $('#modalAgregarProductoAlProceso').modal('hide');
                                    $('#modalProcesosMaquina').modal('hide');
                                    Swal.fire({
                                        title: "FINALIZADO!",
                                        text: "El proceos actualizado con exito.",
                                        icon: "success"
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Error!",
                                        text: JSON.stringify(respuesta),
                                        icon: "warning"
                                    });
                                }
                            },
                            error: function (err) {
                                console.error('Error al cargar facturas:', err);
                                // alert('No se pudieron cargar las facturas.');
                            }
                        });

                    }
                });
            } else {
                $("#formularioAgregacionProductoProceso")[0].reportValidity();
            }
        }

        function imprimirHistorialProceso(tipo) {

            Swal.fire({
                title: 'Generando reporte...',
                text: 'Por favor espera',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('procesos.generaPDFHistorialProceso') }}",
                type: 'POST',
                data: { tipo: tipo },
                xhrFields: {
                    responseType: 'blob'
                },
                success: function (response) {

                    Swal.close(); // 🔥 Cierra el loader

                    let blob = new Blob([response], { type: 'application/pdf' });
                    let link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = "proceso.pdf";
                    link.click();

                },
                error: function (err) {
                    Swal.close(); // 🔥 También cerrar en error

                    console.error(err);
                    Swal.fire({
                        title: "Error",
                        text: "No se pudo generar el PDF",
                        icon: "error"
                    });
                }
            });

        }

        function enviarProcesoFocalizado(datos) {

            console.log(datos);

            Swal.fire({
                title: "Esta seguro de enviar la carga a FOCALIZADO?",
                text: "No podras revertir eso!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Enviar!"
            }).then((result) => {
                if (result.isConfirmed) {

                    let datosE = { d: datos };
                    // let datosE = datos;

                    $.ajax({
                        url: "{{ route('procesos.enviarProcesoFocalizado') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: datosE,
                        success: function (respuesta) {
                            if (respuesta.estado) {
                                ajaxListado();
                                Swal.fire({
                                    title: "FINALIZADO!",
                                    text: "Carga enviado a FOCALIZADO.",
                                    icon: "success"
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: JSON.stringify(respuesta),
                                    icon: "warning"
                                });
                            }
                        },
                        error: function (err) {
                            console.error('Error al cargar facturas:', err);
                            // alert('No se pudieron cargar las facturas.');
                        }
                    });

                }
            });

        }


        //solicitud focalizar

        $("#check_focalizado").change(function () {

            let focalizado = $(this).is(":checked");

            if (focalizado) {

                console.log("⚡ Modo focalizado activado");

                $("#producto_id_solicitud").prop("disabled", true);
                $("#peso_total_ot").prop("disabled", true);
                $("#cantidad_solicitada").prop("disabled", true);
                $("#porcentaje_solicitado").prop("disabled", true);
                $("#btnAgregarProducto").prop("disabled", true);

            } else {

                console.log("📦 Modo solicitud normal");

                $("#producto_id_solicitud").prop("disabled", false);
                $("#peso_total_ot").prop("disabled", false);
                $("#cantidad_solicitada").prop("disabled", false);
                $("#porcentaje_solicitado").prop("disabled", false);
                $("#btnAgregarProducto").prop("disabled", false);

            }

        });


        function guardarSolicitudFocalizado() {
            let facturasSeleccionadas = $('#facturas_seleccionadas').val();
            if (!facturasSeleccionadas || facturasSeleccionadas.length === 0) {

                Swal.fire('Error', 'Seleccione al menos una factura', 'warning');
                return;
            }

            let otsSeleccionadas = [];

            $('#ots_por_factura_container input[type="checkbox"]:checked').each(function () {

                otsSeleccionadas.push({
                    ot_id: $(this).data('ot-id'),
                    nro_ot: $(this).data('nro-ot'),
                    peso: $(this).data('peso'),
                    factura_id: $(this).data('factura-id')
                });

            });

            if (otsSeleccionadas.length === 0) {
                Swal.fire('Error', 'Seleccione al menos una OT', 'warning');
                return;
            }


            $.post("{{ route('solicitudes.store.focalizado') }}", {
                _token: "{{ csrf_token() }}",
                facturas: facturasSeleccionadas,
                ots: otsSeleccionadas
            }, function (response) {
                Swal.fire('OK', 'Proceso enviado a focalizado', 'success');
                $('#modalSolicitudProductos').modal('hide');
                ajaxListado();
            }).fail(function () {
                Swal.fire('Error', 'Error al crear proceso focalizado', 'error');

            });

        }


        //planchado
        function enviarProcesoPlanchado(datos) {

            console.log(datos);

            Swal.fire({
                title: "Esta seguro de enviar la carga a PLANCHADO?",
                text: "No podras revertir eso!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, Enviar!"
            }).then((result) => {
                if (result.isConfirmed) {

                    let datosE = { d: datos };
                    // let datosE = datos;

                    $.ajax({
                        url: "{{ route('procesos.enviarProcesoPlanchado') }}",
                        type: 'POST',
                        dataType: 'json',
                        data: datosE,
                        success: function (respuesta) {
                            if (respuesta.estado) {
                                ajaxListado();
                                Swal.fire({
                                    title: "FINALIZADO!",
                                    text: "Carga enviado a PLANCHADO.",
                                    icon: "success"
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: JSON.stringify(respuesta),
                                    icon: "warning"
                                });
                            }
                        },
                        error: function (err) {
                            console.error('Error al cargar facturas:', err);
                            // alert('No se pudieron cargar las facturas.');
                        }
                    });

                }
            });

        }

    </script>
@endsection