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
<div class="modal fade" id="modalLavanderia" tabindex="-1" aria-labelledby="modalLavanderiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl"> <!-- modal más ancho -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLavanderiaLabel">Nueva Lavandería</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>

            <div class="modal-body">
                <!-- Formulario -->
                <form id="formLavanderia" class="row g-3">
                    <input type="hidden" id="id">
                    <input type="hidden" id="maquinaria_id">

                    <div class="col-md-4">
                        <label for="factura_id" class="form-label">Factura</label>
                        <select id="factura_id" class="form-select">
                            <option value="">Seleccione factura...</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="order_trabajo_id_lavanderia" class="form-label">OT</label>
                        <select id="order_trabajo_id_lavanderia" class="form-select">
                            <option value="">Seleccione OT...</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="producto_id_lavanderia" class="form-label">Producto</label>
                        <select id="producto_id_lavanderia" class="form-select">
                            <option value="">Seleccione producto...</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="tipo_proceso_id" class="form-label">Tipo de Proceso</label>
                        <select id="tipo_proceso_id" class="form-control">
                            <option value="">Seleccione tipo de proceso...</option>
                        </select>
                    </div>


                    <div class="col-md-4">
                        <label for="fecha_ingreso" class="form-label">Fecha Ingreso</label>
                        <input type="datetime-local" id="fecha_ingreso" class="form-control">
                    </div>

                    <div class="col-md-4">
                        <label for="fecha_salida" class="form-label">Fecha Salida</label>
                        <input type="datetime-local" id="fecha_salida" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label for="tiempo" class="form-label">Tiempo</label>
                        <input type="text" id="tiempo" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label for="temperatura" class="form-label">Temperatura</label>
                        <input type="text" id="temperatura" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label for="ph" class="form-label">PH</label>
                        <input type="text" id="ph" class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label for="rb" class="form-label">RB</label>
                        <input type="text" id="rb" class="form-control">
                    </div>

                    <div class="col-12">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea id="descripcion" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-12 text-end">
                        <button type="button" class="btn btn-primary" id="agregarAlListado">Agregar al listado</button>
                    </div>
                </form>

                <hr>

                <!-- Tabla Listado Temporal -->
                <div class="table-responsive">
                    <table id="tablaListadoTemporal" class="table table-striped table-bordered mt-2">
                        <thead class="table-primary">
                            <tr>
                                <th>Factura</th>
                                <th>OT</th>
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
                    <button type="button" class="btn btn-success" id="guardarListado">Guardar listado</button>
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

                <div class="row">
                    <div class="col-md-6">

                        <!-- FACTURA -->
                        <div class="mb-3">
                            <label>Factura</label>
                            <select id="factura_id_solicitud" class="form-select" data-dropdown-parent="#modalSolicitudProductos">
                                <option value="">Seleccione factura...</option>
                                @foreach($facturas as $factura)
                                    <option value="{{ $factura->id }}">{{ $factura->numero_factura }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="col-md-6">

                        <!-- ORDEN DE TRABAJO -->
                        <div class="mb-3">
                            <label>Orden de Trabajo (OT agrupadas)</label>
                            <select id="ot_agrupada" class="form-select">
                                <option value="">Seleccione OT...</option>
                            </select>
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <!-- PRODUCTO -->
                        <div class="mb-3">
                            <label>Producto (con stock)</label>
                            <select id="producto_id_solicitud" class="form-select"  data-dropdown-parent="#modalSolicitudProductos">
                                <option value="">Seleccione producto...</option>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>Porcentaje (%)</label>
                            <input type="number" step="0.01" id="porcentaje_solicitado" class="form-control"
                                placeholder="Ej: 3">
                        </div>
                    </div>
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
                            {{-- <th>Código Compra</th> --}}
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

            $('#factura_id_solicitud, #producto_id_solicitud').select2()


            // // Inicializar Repeater
            // if (typeof $.fn.repeater === "function") {
            //     $('#kt_docs_repeater_advanced').repeater({
            //         initEmpty: false,
            //         show: function () { $(this).slideDown(); },
            //         hide: function (deleteElement) {
            //             $(this).slideUp(deleteElement);
            //         }
            //     });
            // } else {
            //     console.error("jQuery Repeater no está cargado");
            // }

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

            function recargarListado() {
                $.get("{{ route('procesos.ajaxListado') }}", function (res) {
                    if (res.estado) $('#table_listado').html(res.data.listado);
                    else $('#table_listado').html('<p class="text-danger text-center">Error al cargar los procesos</p>');
                });
            }




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


        function cargarProductosLavanderia(ot_id) {
            if (!ot_id) return;

            // Construir la URL reemplazando :ot_id
            let url = "{{ route('procesos.productosAprobadosPorOT', ['ot_id' => ':ot_id']) }}";
            url = url.replace(':ot_id', ot_id);

            $.get(url, function (data) {
                console.log("Productos Lavandería para OT", ot_id, data);
                let select = $("#producto_id_lavanderia");
                select.empty();
                select.append('<option value="">Seleccione...</option>');
                data.forEach(item => select.append(`<option value="${item.id}">${item.nombre}</option>`));
            });
        }





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
            cargarFacturas();

            // Vaciar selects de OT y Producto


            // Mostrar modal
            $('#modalLavanderia').modal('show');
        }

        function cargarTiposProceso() {
            $.get("{{ route('procesos.listaTiposProceso') }}", function (data) {
                let select = $("#tipo_proceso_id");
                select.empty();
                select.append('<option value="">Seleccione...</option>');
                data.forEach(item => select.append(`<option value="${item.id}">${item.nombre}</option>`));
            });
        }





        $('#order_trabajo_id_lavanderia').change(function () {
            let orderTrabajoId = $(this).val();
            let select = $('#producto_id_lavanderia');

            select.html('<option value="">Cargando productos...</option>');

            if (orderTrabajoId) {
                $.get("{{ route('factura.productos') }}", { order_trabajo_id: orderTrabajoId }, function (data) {
                    select.empty();
                    select.append('<option value="">Seleccione producto...</option>');
                    data.forEach(p => {
                        select.append(`<option value="${p.id}">${p.nombre} - ${p.tipo}</option>`);
                    });
                }).fail(function (err) {
                    console.error('Error al cargar productos:', err);
                    select.html('<option value="">Error al cargar productos</option>');
                });
            } else {
                select.html('<option value="">Seleccione OT primero</option>');
            }
        });

        function agregarProcesoAlListado() {
            let proceso = {
                order_trabajo_id: $('#order_trabajo_id_lavanderia').val(),
                producto_id: $('#producto_id_lavanderia').val(),
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
                estado: 'TRABAJANDO'
            };

            // Validaciones básicas
            if (!proceso.order_trabajo_id || !proceso.producto_id || !proceso.tipo_proceso_id) {
                alert("Debe seleccionar OT, producto y tipo de proceso.");
                return;
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
                                        <td>${$('#factura_id option:selected').text()}</td>
                                        <td>${$('#order_trabajo_id_lavanderia option:selected').text()}</td>
                                        <td>${$('#producto_id_lavanderia option:selected').text()}</td>
                                        <td>${$('#tipo_proceso_id option:selected').text()}</td>
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
            $('#tipo_proceso_id').val('');
            $('#fecha_ingreso').val('');
            $('#fecha_salida').val('');
            $('#tiempo').val('');
            $('#temperatura').val('');
            $('#ph').val('');
            $('#rb').val('');
            $('#descripcion').val('');
        }





        //solicitud


        let solicitudTemporal = [];
        let otSeleccionada = [];

        // FACTURA → OTs
        $('#factura_id_solicitud').on('change', function () {
            let facturaId = $(this).val();
            let select = $('#ot_agrupada');
            select.html('<option value="">Cargando...</option>');
            otSeleccionada = null;

            if (!facturaId) {
                select.html('<option value="">Seleccione OT...</option>');
                return;
            }

            $.get("{{ route('solicitudes.otsPorFactura', ':id') }}".replace(':id', facturaId), function (data) {
                select.empty().append('<option value="">Seleccione OT...</option>');
                data.forEach(ot => {
                    select.append(`<option
                                                                                                                                                                data-ids='${JSON.stringify(ot.ids)}'
                                                                                                                                                                data-peso='${ot.peso_total}'>
                                                                                                                                                                OT ${ot.nro_ot} (Peso: ${ot.peso_total})
                                                                                                                                                            </option>`);
                });
            });
        });




        // OT → actualizar cantidad (suma de OT)
        // $('#ot_agrupada').on('change', function () {
        //     let selected = $(this).find('option:selected');
        //     let otIds = selected.val() ? selected.val().split(',').map(Number) : [];
        //     otSeleccionada = otIds;

        //     let pesoMatch = selected.text().match(/\(Peso: ([\d\.]+)\)/);
        //     let peso = pesoMatch ? parseFloat(pesoMatch[1]) : 0;
        //     $('#cantidad_solicitada').val(peso);

        //     // Cargar códigos de compra según las OT seleccionadas
        //     if (otIds.length) {
        //         $.get("{{ route('solicitudes.codigosCompra') }}", { orden_trabajo_id: otIds }, function (data) {
        //             let select = $('#codigo_compra');
        //             select.empty().append('<option value="">Seleccione código...</option>');
        //             data.forEach(c => select.append(`<option value="${c.codigo_compra}">${c.codigo_compra} (Stock: ${c.stock})</option>`));
        //         });
        //     }
        // });

        $('#ot_agrupada').on('change', function () {


            let selected = $(this).find('option:selected');

            if (!selected.val()) {
                otSeleccionada = null;
                $('#cantidad_solicitada').val('');
                return;
            }

            otSeleccionada = {
                ids: JSON.parse(selected.attr('data-ids')),
                peso_total: parseFloat(selected.attr('data-peso'))
            };

            $('#cantidad_solicitada').val(otSeleccionada.peso_total);
        });



        // CÓDIGO → Productos con stock
        $('#codigo_compra').on('change', function () {
            let codigo = $(this).val();
            let select = $('#producto_id_solicitud');
            select.html('<option value="">Cargando...</option>');

            if (!codigo) return;

            $.get("{{ route('solicitudes.productosConStock') }}", { codigo_compra: codigo }, function (data) {
                select.empty().append('<option value="">Seleccione producto...</option>');
                data.forEach(p => {
                    select.append(`<option value="${p.producto_id}">${p.nombre} (Stock: ${p.stock})</option>`);
                });
            });
        });




        // Agregar producto al listado temporal
        function agregarProductoTemporal() {

            let productoId    = $('#producto_id_solicitud').val();
            let productoTexto = $('#producto_id_solicitud option:selected').text();

            // let codigoCompra  = $('#codigo_compra').val();
            let cantidadTotal = parseFloat($('#cantidad_solicitada').val());
            let porcentaje    = parseFloat($('#porcentaje_solicitado').val()) || 0;

            // if (!productoId || !codigoCompra || !otSeleccionada || !cantidadTotal || !porcentaje) {
            if (!productoId || !otSeleccionada || !cantidadTotal || !porcentaje) {
                Swal.fire('Error', 'Complete todos los campos antes de agregar', 'warning');
                return;
            }

            let cantidadCalculada = (cantidadTotal * porcentaje) / 100;

            solicitudTemporal.push({
                producto_id: productoId,
                producto_nombre: productoTexto,
                // codigo_compra: codigoCompra,
                orden_trabajo_ids: otSeleccionada.ids,
                cantidad: cantidadCalculada,
                porcentaje: porcentaje
            });

            actualizarTablaTemporal();
        }



        // Actualizar tabla temporal
        function actualizarTablaTemporal() {
            let tbody = $('#tabla_solicitud_temporal tbody');
            tbody.empty();
            let ordenesTrabasjo = @json($ordenes);
            solicitudTemporal.forEach((item, index) => {
                let nroOtes = item.orden_trabajo_ids[0];
                let f = ordenesTrabasjo.find(o => o.id == nroOtes);
                tbody.append(`
                                <tr>
                                    <td>${item.producto_nombre}</td>
                                    <td>N° ${f.nro_ot}</td>
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

        function cargarCodigosCompra() {
            $.get("{{ route('solicitudes.codigosCompra') }}", function (data) {
                let select = $('#codigo_compra');
                select.empty().append('<option value="">Seleccione código...</option>');
                data.forEach(c => {
                    select.append(`<option value="${c.codigo_compra}">${c.codigo_compra} (Stock: ${c.stock})</option>`);
                });
            });
        }

        // Llamar al cargar modal
        cargarCodigosCompra();



        function abrirModalSolicitud() {
            // Si estás usando Bootstrap 5
            let modal = new bootstrap.Modal(document.getElementById('modalSolicitudProductos'));
            modal.show();
        }

    </script>
@endsection
