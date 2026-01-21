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
                                <!-- FILA 1: OT, Producto, Tipo Proceso -->
                                <div class="form-group row mb-3 g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">OT</label>
                                        <select class="form-select order_trabajo_id" name="order_trabajo_id">
                                            <option value="">Seleccione OT...</option>
                                            @foreach($ordenes as $ot)
                                                <option value="{{ $ot->id }}">OT: {{ $ot->id }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Producto</label>
                                        <select class="form-select producto_id" name="producto_id">
                                            <option value="">Seleccione producto...</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Tipo Proceso</label>
                                        <select class="form-select tipo_proceso_id" name="tipo_proceso_id">
                                            <option value="">Seleccione...</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- FILA 2: Fecha, Tiempo, Temp, PH, RB, Descripción, Eliminar -->
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

                                    <!-- <div class="col-md-1">
                                        <a href="javascript:;" data-repeater-delete
                                            class="btn btn-sm btn-light-danger w-100">
                                            <i class="ki-duotone ki-trash fs-3"></i>
                                        </a>
                                    </div> -->
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




<div class="modal fade" id="modalSolicitudProductos" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Solicitud de Productos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                {{-- SELECT OT --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Orden de Trabajo (OT)</label>
                        <select id="order_trabajo_id_solicitud" class="form-select">
                            <option value="">Seleccione OT...</option>

                            @foreach($ordenes as $ot)
                                <option value="{{ $ot->id }}">
                                    OT: {{ $ot->id }}
                                    @if($ot->factura)
                                        - Factura: {{ $ot->factura->numero_factura }}
                                    @else
                                        - SIN FACTURA
                                    @endif
                                </option>
                            @endforeach

                        </select>

                    </div>
                </div>



                {{-- PRODUCTO --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Producto</label>
                        <select id="producto_id_solicitud" class="form-select">
                            <option value="">Cargando...</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label>Cantidad</label>
                        <input type="number" id="cantidad" class="form-control">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-success w-100" onclick="agregarProducto()">+</button>
                    </div>
                </div>

                {{-- TABLA DETALLE --}}
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody id="detalleProductos"></tbody>
                </table>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="guardarSolicitud()">
                    Guardar solicitud
                </button>
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

        //solicitud

        let productosSolicitud = [];

        /* ABRIR MODAL */


        function abrirModalSolicitud() {
            productosSolicitud = [];
            $("#detalleProductos").html('');
            $("#order_trabajo_id_solicitud").val('');
            $("#producto_id_solicitud").val('');
            $("#cantidad").val('');
            //  cargarOTs('order_trabajo_id_solicitud'); // ID correcto
            cargarProductosSolicitud(); // ID correcto
            $('#modalSolicitudProductos').modal('show');
        }


        /* RENDER TABLA */
        function renderTabla() {
            let html = '';
            productosSolicitud.forEach((item, index) => {
                html += `
                                                                                                                                                                                                                                                            <tr>
                                                                                                                                                                                                                                                                <td>${item.producto}</td>
                                                                                                                                                                                                                                                                <td>${item.cantidad}</td>
                                                                                                                                                                                                                                                                <td>
                                                                                                                                                                                                                                                                    <button class="btn btn-danger btn-sm"
                                                                                                                                                                                                                                                                            onclick="eliminarProducto(${index})">
                                                                                                                                                                                                                                                                        X
                                                                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                                                                </td>
                                                                                                                                                                                                                                                            </tr>
                                                                                                                                                                                                                                                        `;
            });
            $("#detalleProductos").html(html);
        }

        /* ELIMINAR */
        function eliminarProducto(index) {
            productosSolicitud.splice(index, 1);
            renderTabla();
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

        function cargarProductosSolicitud() {
            $.get("{{ route('procesos.productosMovimientos') }}", function (data) {
                console.log("Productos Solicitud:", data); // <-- para depuración
                let select = $("#producto_id_solicitud");
                select.empty();
                select.append('<option value="">Seleccione...</option>');
                data.forEach(item => select.append(`<option value="${item.id}">${item.nombre} - Stock: ${item.stock}</option>`));
            });
        }

        // Guardar solicitud: corregir ID de OT



        function guardarSolicitud() {
            let ordenTrabajoId = $("#order_trabajo_id_solicitud").val();

            if (!ordenTrabajoId || productosSolicitud.length === 0) {
                Swal.fire('Error', 'Seleccione una OT y agregue productos', 'error');
                return;
            }

            $.ajax({
                url: "{{ route('solicitudes.store') }}",
                type: "POST",
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                data: {
                    orden_trabajo_id: ordenTrabajoId,
                    productos: productosSolicitud,
                    estado: 'EN ESPERA' // <-- agregamos el estado aquí
                },
                success: function () {
                    Swal.fire('OK', 'Solicitud registrada correctamente', 'success');
                    $('#modalSolicitudProductos').modal('hide');
                },
                error: function () {
                    Swal.fire('Error', 'No se pudo guardar la solicitud', 'error');
                }
            });
        }



        function agregarProducto() {
            let ordenTrabajoId = $("#order_trabajo_id_solicitud").val(); // ID correcto
            let productoId = $("#producto_id_solicitud").val(); // ID correcto
            let productoText = $("#producto_id_solicitud option:selected").text();
            let cantidad = $("#cantidad").val();

            if (!ordenTrabajoId) {
                Swal.fire('Atención', 'Seleccione una OT', 'warning');
                return;
            }

            if (!productoId || !cantidad) {
                Swal.fire('Atención', 'Seleccione producto y cantidad', 'warning');
                return;
            }

            productosSolicitud.push({
                producto_id: productoId,
                producto: productoText,
                cantidad: cantidad
            });

            renderTabla();
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
            $(document).on('change', '.order_trabajo_id', function () {
                let fila = $(this).closest('[data-repeater-item]');
                let ot_id = $(this).val();
                let productoSelect = fila.find('.producto_id');

                if (!ot_id) {
                    productoSelect.html('<option value="">Seleccione producto...</option>');
                    return;
                }

                $.get("{{ route('procesos.productosSolicitudesAceptadas') }}", { ot_id: ot_id }, function (data) {
                    console.log("Productos recibidos:", data);
                    productoSelect.empty();
                    productoSelect.append('<option value="">Seleccione producto...</option>');
                    data.forEach(p => productoSelect.append(`<option value="${p.id}">${p.nombre}</option>`));
                });

                let tipoSelect = fila.find('.tipo_proceso_id');
                $.get("{{ route('procesos.listaTiposProceso') }}", function (tipos) {
                    tipoSelect.empty();
                    tipoSelect.append('<option value="">Seleccione...</option>');
                    tipos.forEach(t => tipoSelect.append(`<option value="${t.id}">${t.nombre}</option>`));
                });
            });

            // Guardar cada proceso individual
            $('#guardarProcesosBtn').click(function () {
                let filas = $('#kt_docs_repeater_advanced [data-repeater-item]').filter(':visible'); // SOLO filas visibles
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

                    // Validación básica
                    if (!datos.order_trabajo_id || !datos.producto_id || !datos.tipo_proceso_id || !datos.fecha_ingreso) {
                        errores = true;
                        return false; // rompe el each
                    }

                    // Enviar por AJAX
                    $.ajax({
                        url: "{{ route('procesos.guardar') }}",
                        method: "POST",
                        data: datos,
                        async: false,
                        success: function (res) {
                            if (res.estado) {
                                console.log("Proceso guardado:", res.mensaje);
                            } else {
                                Swal.fire('Error', res.mensaje, 'error');
                            }
                        },
                        error: function (xhr) {
                            console.error(xhr.responseJSON);
                        }
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
        });



        function recargarListado() {
            $.get("{{ route('procesos.ajaxListado') }}", function (res) {
                if (res.estado) {
                    $('#table_listado').html(res.data.listado);
                } else {
                    $('#table_listado').html('<p class="text-danger text-center">Error al cargar los procesos</p>');
                }
            });
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