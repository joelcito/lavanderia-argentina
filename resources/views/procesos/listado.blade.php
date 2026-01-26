@extends('layouts.app')
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

            <h3 class="fw-bold mb-3">Maquinarias Disponibles</h3>
            <div class="d-flex mb-4 overflow-auto">
                @foreach ($maquinarias as $m)
                    <div class="maquina-container"
                        style="border:2px solid {{ $m->estado_maquina == 'DISPONIBLE' ? '#28a745' : '#dc3545' }};"
                        onclick="modalNuevaLavanderiaConMaquinaria({{ $m->id }})">
                        <div class="fw-bold">{{ ucfirst($m->tipo) }}</div>

                        <div class="text-muted small">
                            Equipo N° {{ $m->numero}}
                        </div>
                        <!-- Estado -->
                        <span class="badge {{ $m->estado_maquina == 'DISPONIBLE' ? 'bg-success' : 'bg-danger' }}">
                            {{ $m->estado_maquina }}
                        </span>
                        <br>

                        <!-- OTs activas -->
                        <span class="badge bg-primary mt-1">
                            OTs activas: {{ $m->procesos_activos }}
                        </span>
                        <br>

                        <!-- Imagen -->
                        @if ($m->tipo == 'lavadora')
                            <img src="{{ asset('assets/img/lavadora.jpg') }}" alt="Lavadora">
                        @else
                            <img src="{{ asset('assets/img/secadora.png') }}" alt="Secadora">
                        @endif
                    </div>
                @endforeach
            </div>

            <button class="btn btn-sm btn-primary" onclick="abrirModalSolicitud()">
                Solicitar productos
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
<div class="modal fade" id="modalLavanderia" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-light-info">
                <h5 class="modal-title fw-bold">Registrar Proceso de Lavandería</h5>
                <button type="button" class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="maquinaria_id" name="maquinaria_id">

                <div id="kt_docs_repeater_advanced">
                    <div class="form-group">
                        <div data-repeater-list="procesos_lavanderia">
                            <div data-repeater-item>
                                <div class="form-group row mb-3 g-3">
                                    <!-- Factura -->
                                    <div class="col-md-4">
                                        <label class="form-label">Factura</label>
                                        <select class="form-select factura_id" name="factura_id">
                                            <option value="">Seleccione factura...</option>
                                            @foreach($facturas as $factura)
                                                <option value="{{ $factura->id }}">{{ $factura->numero_factura }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- OT -->
                                    <div class="col-md-4">
                                        <label class="form-label">OT</label>
                                        <select class="form-select order_trabajo_id" name="order_trabajo_id">
                                            <option value="">Seleccione OT...</option>
                                        </select>
                                    </div>

                                    <!-- Producto -->
                                    <div class="col-md-4">
                                        <label class="form-label">Producto</label>
                                        <select class="form-select producto_id" name="producto_id">
                                            <option value="">Seleccione producto...</option>
                                        </select>
                                    </div>

                                    <!-- Tipo Proceso -->
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label">Tipo Proceso</label>
                                        <select class="form-select tipo_proceso_id" name="tipo_proceso_id">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-3 g-3 align-items-end">
                                    <div class="col-md-2">
                                        <label class="form-label">Fecha Ingreso</label>
                                        <input type="datetime-local" class="form-control fecha_ingreso"
                                            name="fecha_ingreso" />
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Fecha Salida</label>
                                        <input type="datetime-local" class="form-control fecha_salida"
                                            name="fecha_salida" />
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">Tiempo</label>
                                        <input type="number" class="form-control tiempo" name="tiempo"
                                            placeholder="Tiempo" />
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">°C</label>
                                        <input type="number" class="form-control temperatura" name="temperatura"
                                            placeholder="°C" />
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">pH</label>
                                        <input type="number" class="form-control ph" name="ph" placeholder="pH" />
                                    </div>
                                    <div class="col-md-1">
                                        <label class="form-label">RB</label>
                                        <input type="text" class="form-control rb" name="rb" placeholder="RB" />
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Descripción</label>
                                        <input type="text" class="form-control descripcion" name="descripcion"
                                            placeholder="Descripción" />
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center mt-4">
                                        <a href="javascript:;" data-repeater-delete
                                            class="btn btn-sm btn-light-danger w-100 d-flex justify-content-center">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <a href="javascript:;" data-repeater-create class="btn btn-flex btn-light-primary">
                            <i class="ki-duotone ki-plus fs-3"></i> Agregar fila
                        </a>
                    </div>
                </div>

                <button type="button" class="btn btn-success mt-3" id="guardarProcesosBtn">Guardar procesos</button>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
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

                <!-- FACTURA -->
                <div class="mb-3">
                    <label>Factura</label>
                    <select id="factura_id_solicitud" class="form-select">
                        <option value="">Seleccione factura...</option>
                        @foreach($facturas as $factura)
                            <option value="{{ $factura->id }}">{{ $factura->numero_factura }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- ORDEN DE TRABAJO -->
                <div class="mb-3">
                    <label>Orden de Trabajo (OT agrupadas)</label>
                    <select id="ot_agrupada" class="form-select">
                        <option value="">Seleccione OT...</option>
                    </select>
                </div>

                <!-- CÓDIGO DE COMPRA -->
                <div class="mb-3">
                    <label>Código de Compra</label>
                    <select id="codigo_compra" class="form-select">
                        <option value="">Seleccione código...</option>
                    </select>
                </div>

                <!-- PRODUCTO -->
                <div class="mb-3">
                    <label>Producto (con stock)</label>
                    <select id="producto_id_solicitud" class="form-select">
                        <option value="">Seleccione producto...</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Porcentaje (%)</label>
                    <input type="number" step="0.01" id="porcentaje_solicitado" class="form-control"
                        placeholder="Ej: 3">
                </div>

                <!-- CANTIDAD -->
                <div class="mb-3">
                    <label>Cantidad (Peso total OT)</label>
                    <input type="number" step="0.01" id="cantidad_solicitada" class="form-control" readonly>
                </div>

                <!-- Botón Agregar al listado temporal -->
                <div class="mb-3">
                    <button class="btn btn-success" type="button" onclick="agregarProductoTemporal()">Agregar al
                        listado</button>
                </div>

                <!-- Tabla de listado temporal -->
                <table class="table table-bordered" id="tabla_solicitud_temporal">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Código Compra</th>
                            <th>OT(s)</th>
                            <th>Porcentaje (%)</th>
                            <th>Cantidad</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Filas agregadas dinámicamente aquí -->
                    </tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="guardarSolicitud()">Guardar Solicitud</button>
                <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>

@stop

@section('js')


    <!-- jQuery (si no está en el layout, de lo contrario omítelo) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- jQuery Repeater -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>

    <!-- SweetAlert y Datatables -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            ajaxListado();
            actualizarTemporizadores(); // iniciar temporizador
        });

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

        function modalNuevaLavanderiaConMaquinaria(maquinaria_id) {
            $.post("{{ route('procesos.infoMaquinaria') }}", { id: maquinaria_id }, function (res) {
                if (res.estado_maquina === "NO DISPONIBLE") {
                    Swal.fire('No disponible', 'Esta máquina ya tiene 3 procesos activos.', 'warning');
                    return;
                }

                // Limpiar formulario
                $('#id').val(0);


                $('#order_trabajo_id_lavanderia').val('');
                $('#producto_id_lavanderia').val('');

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
                cargarProductosLavanderia();
                cargarTiposProceso();
                cargarOTs('order_trabajo_id_lavanderia', function (ot_id) {
                    // Cada vez que se seleccione una OT, cargar los productos aprobados en el repeater
                    cargarProductosPorOT(ot_id);
                });
                $('#modalLavanderia').modal('show');



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


        // Cuando cambia la factura, cargar las OTs correspondientes
        $(document).on('change', '.factura_id', function () {
            let fila = $(this).closest('[data-repeater-item]');
            let factura_id = $(this).val();
            let otSelect = fila.find('.order_trabajo_id');
            let productoSelect = fila.find('.producto_id');

            otSelect.html('<option value="">Cargando...</option>');
            productoSelect.html('<option value="">Seleccione producto...</option>');

            if (!factura_id) {
                otSelect.html('<option value="">Seleccione OT...</option>');
                return;
            }

            $.get("{{ route('procesos.listaOTsPorFactura') }}", { factura_id: factura_id }, function (data) {
                otSelect.empty().append('<option value="">Seleccione OT...</option>');
                if (data.length) {
                    data.forEach(ot => otSelect.append(`<option value="${ot.id}">OT ${ot.nro_ot}</option>`));
                } else {
                    otSelect.append('<option value="">No hay OTs para esta factura</option>');
                }
            });
        });





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
                url: "{{ route('procesos.guardar') }}",
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


        function cargarProductosLavanderia(ot_id) {
            if (!ot_id) return;

            $.get("{{ route('procesos.productosSolicitudesAceptadas') }}", { ot_id: ot_id }, function (data) {
                console.log("Productos Lavandería para OT", ot_id, data);
                let select = $("#producto_id_lavanderia");
                select.empty();
                select.append('<option value="">Seleccione...</option>');
                data.forEach(item => select.append(`<option value="${item.id}">${item.nombre}</option>`));
            });
        }



        $('#order_trabajo_id_lavanderia').on('change', function () {
            let ot_id = $(this).val();
            cargarProductosLavanderia(ot_id);
        });




        function cargarProductosPorOT(ot_id) {
            $.get("{{ route('procesos.productosSolicitudesAceptadas') }}", { ot_id: ot_id }, function (data) {
                let repeaterContainer = $("#kt_docs_repeater_advanced [data-repeater-list]");
                repeaterContainer.empty();

                data.forEach(producto => {
                    repeaterContainer.append(`
                                                                                                                                        <div data-repeater-item>
                                                                                                                                            <div class="form-group row mb-5">
                                                                                                                                                <div class="col-md-4">
                                                                                                                                                    <label class="form-label">Producto</label>
                                                                                                                                                    <input type="text" class="form-control" value="${producto.nombre}" readonly />
                                                                                                                                                    <input type="hidden" name="producto_id" value="${producto.id}" />
                                                                                                                                                </div>
                                                                                                                                                <div class="col-md-3">
                                                                                                                                                    <label class="form-label">Tipo Proceso</label>
                                                                                                                                                    <select class="form-select" name="tipo_proceso_id">
                                                                                                                                                        <option value="">Seleccione...</option>
                                                                                                                                                    </select>
                                                                                                                                                </div>
                                                                                                                                                <div class="col-md-2">
                                                                                                                                                    <label class="form-label">Fecha Ingreso</label>
                                                                                                                                                    <input type="datetime-local" class="form-control" name="fecha_ingreso" />
                                                                                                                                                </div>
                                                                                                                                                <div class="col-md-2">
                                                                                                                                                    <label class="form-label">Fecha Salida</label>
                                                                                                                                                    <input type="datetime-local" class="form-control" name="fecha_salida" />
                                                                                                                                                </div>
                                                                                                                                                <div class="col-md-2 mt-5">
                                                                                                                                                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger">
                                                                                                                                                        <i class="ki-duotone ki-trash fs-3"></i> Eliminar
                                                                                                                                                    </a>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    `);
                });

                // Inicializa repeater **solo si la librería ya está disponible**
                if (typeof $.fn.repeater === "function" && !$('#kt_docs_repeater_advanced').data('repeater-initialized')) {
                    $('#kt_docs_repeater_advanced').repeater({
                        initEmpty: false,
                        show: function () { $(this).slideDown(); },
                        hide: function (deleteElement) {
                            $(this).slideUp(deleteElement);
                        }
                    });
                    $('#kt_docs_repeater_advanced').data('repeater-initialized', true);
                } else if (typeof $.fn.repeater !== "function") {
                    console.error("jQuery Repeater no está cargado");
                }
            });
        }


        //ororo

        $(document).ready(function () {

            // Inicializar Repeater
            if (typeof $.fn.repeater === "function") {
                $('#kt_docs_repeater_advanced').repeater({
                    initEmpty: false,
                    show: function () { $(this).slideDown(); },
                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                });
            } else {
                console.error("jQuery Repeater no está cargado");
            }

            // Cada vez que se selecciona una OT en cualquier fila

            // Cuando cambia la OT → cargar productos de esa OT
            $(document).on('change', '.order_trabajo_id', function () {
                let fila = $(this).closest('[data-repeater-item]');
                let ot_id = $(this).val();
                let productoSelect = fila.find('.producto_id');
                let tipoSelect = fila.find('.tipo_proceso_id');

                productoSelect.html('<option value="">Cargando...</option>');
                tipoSelect.html('<option value="">Cargando...</option>');

                if (!ot_id) {
                    productoSelect.html('<option value="">Seleccione producto...</option>');
                    tipoSelect.html('<option value="">Seleccione...</option>');
                    return;
                }

                // Productos aprobados por OT
                $.get("{{ route('procesos.productosSolicitudesAceptadas') }}", { ot_id: ot_id }, function (data) {
                    productoSelect.empty().append('<option value="">Seleccione producto...</option>');
                    data.forEach(p => productoSelect.append(`<option value="${p.id}">${p.nombre}</option>`));
                });

                // Tipos de proceso
                $.get("{{ route('procesos.listaTiposProceso') }}", function (tipos) {
                    tipoSelect.empty().append('<option value="">Seleccione...</option>');
                    tipos.forEach(t => tipoSelect.append(`<option value="${t.id}">${t.nombre}</option>`));
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
                        url: "{{ route('procesos.guardar') }}",
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

            function recargarListado() {
                $.get("{{ route('procesos.ajaxListado') }}", function (res) {
                    if (res.estado) $('#table_listado').html(res.data.listado);
                    else $('#table_listado').html('<p class="text-danger text-center">Error al cargar los procesos</p>');
                });
            }

        });





        //solicitud


        let solicitudTemporal = [];
        let otSeleccionada = [];

        // FACTURA → OTs
        $('#factura_id_solicitud').on('change', function () {
            let facturaId = $(this).val();
            if (!facturaId) return;

            $('#ot_agrupada').html('<option value="">Cargando...</option>');

            $.get("{{ route('solicitudes.otsPorFactura', ':id') }}".replace(':id', facturaId), function (data) {
                let select = $('#ot_agrupada');
                select.empty();
                select.append('<option value="">Seleccione OT...</option>');
                data.forEach(ot => {
                    select.append(`<option value='${ot.ids}'>OT ${ot.nro_ot} (Peso: ${ot.peso_total})</option>`);
                });
            });
        });


        // OT → actualizar cantidad (suma de OT)
        $('#ot_agrupada').on('change', function () {
            let selected = $(this).find('option:selected');
            let otIds = selected.val() ? selected.val().split(',').map(Number) : [];
            otSeleccionada = otIds;

            let pesoMatch = selected.text().match(/\(Peso: ([\d\.]+)\)/);
            let peso = pesoMatch ? parseFloat(pesoMatch[1]) : 0;
            $('#cantidad_solicitada').val(peso);

            // Cargar códigos de compra según las OT seleccionadas
            if (otIds.length) {
                $.get("{{ route('solicitudes.codigosCompra') }}", { orden_trabajo_id: otIds }, function (data) {
                    let select = $('#codigo_compra');
                    select.empty().append('<option value="">Seleccione código...</option>');
                    data.forEach(c => select.append(`<option value="${c.codigo_compra}">${c.codigo_compra} (Stock: ${c.stock})</option>`));
                });
            }
        });

        // CÓDIGO → Productos con stock
        $('#codigo_compra').on('change', function () {
            let codigo = $(this).val();
            $('#producto_id_solicitud').html('<option value="">Cargando...</option>');

            if (!codigo) return;

            $.get("{{ route('solicitudes.productosConStock') }}", { codigo_compra: codigo }, function (data) {
                let select = $('#producto_id_solicitud');
                select.empty().append('<option value="">Seleccione producto...</option>');
                data.forEach(p => select.append(`<option value="${p.producto_id}">${p.nombre} (Stock: ${p.stock})</option>`));
            });
        });

        // Agregar producto al listado temporal
        function agregarProductoTemporal() {
            let productoId = $('#producto_id_solicitud').val();
            let productoTexto = $('#producto_id_solicitud option:selected').text();
            let codigoCompra = $('#codigo_compra').val();

            let cantidadTotal = parseFloat($('#cantidad_solicitada').val());
            let porcentaje = parseFloat($('#porcentaje_solicitado').val()) || 0;

            if (!productoId || !codigoCompra || !otSeleccionada.length || !cantidadTotal || !porcentaje) {
                Swal.fire('Error', 'Complete todos los campos antes de agregar', 'warning');
                return;
            }

            // Calculamos la cantidad según el porcentaje
            let cantidadCalculada = (cantidadTotal * porcentaje) / 100;

            solicitudTemporal.push({
                producto_id: productoId,
                codigo_compra: codigoCompra,
                orden_trabajo_id: otSeleccionada,
                cantidad: cantidadCalculada,
                porcentaje: porcentaje
            });

            actualizarTablaTemporal();
        }


        // Actualizar tabla temporal
        function actualizarTablaTemporal() {
            let tbody = $('#tabla_solicitud_temporal tbody');
            tbody.empty();

            solicitudTemporal.forEach((item, index) => {
                let productoNombre = $('#producto_id_solicitud option:selected').text();
                tbody.append(`
                                                            <tr>
                                                                <td>${productoNombre}</td>
                                                                <td>${item.codigo_compra}</td>
                                                                <td>${item.orden_trabajo_id.join(', ')}</td>
                                                                <td>${item.porcentaje}%</td>
                                                                <td>${item.cantidad.toFixed(2)}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-danger btn-sm" onclick="eliminarProductoTemporal(${index})">Eliminar</button>
                                                                </td>
                                                            </tr>
                                                        `);
            });
        }

        // Eliminar producto temporal
        function eliminarProductoTemporal(index) {
            solicitudTemporal.splice(index, 1);
            actualizarTablaTemporal();
        }

        // Guardar todas las solicitudes
        function guardarSolicitud() {
            if (!solicitudTemporal.length) {
                Swal.fire('Error', 'Agregue al menos un producto al listado', 'warning');
                return;
            }

            $.post("{{ route('solicitudes.store') }}", {
                _token: "{{ csrf_token() }}",
                solicitudes: solicitudTemporal
            }, function (res) {
                Swal.fire('OK', 'Solicitudes registradas correctamente', 'success');
                $('#modalSolicitudProductos').modal('hide');
                solicitudTemporal = [];
                actualizarTablaTemporal();
            }).fail(function (xhr) {
                console.error(xhr.responseJSON);
                Swal.fire('Error', 'Ocurrió un error al guardar la solicitud', 'error');
            });
        }

        function abrirModalSolicitud() {
            // Si estás usando Bootstrap 5
            let modal = new bootstrap.Modal(document.getElementById('modalSolicitudProductos'));
            modal.show();
        }

    </script>






    </script>
@endsection

@section('js')
    <!-- jQuery (solo si no está en tu layout) -->
    {{--
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}

    <!-- jQuery Repeater -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.repeater/1.2.1/jquery.repeater.min.js"></script>

    <!-- SweetAlert y Datatables -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>

    <script>
        $(document).ready(function () {

            // Inicializar Repeater
            if (typeof $.fn.repeater === "function") {
                $('#kt_docs_repeater_advanced').repeater({
                    initEmpty: true,
                    show: function () { $(this).slideDown(); },
                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                });
            } else {
                console.error("jQuery Repeater no está cargado");
            }

            // Aquí van todas tus funciones AJAX y eventos
            ajaxListado();
            actualizarTemporizadores();
        });
    </script>
@endsection