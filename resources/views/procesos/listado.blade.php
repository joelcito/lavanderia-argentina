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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-info">
                <h5 class="modal-title fw-bold">Registrar Proceso de Lavandería</h5>
                <button type="button" class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="id">
                <div class="row">
                    <input type="hidden" id="maquinaria_id" name="maquinaria_id">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Producto</label>
                        <select id="producto_id" class="form-select">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Tipo de Proceso</label>
                        <select id="tipo_proceso_id" class="form-select">
                            <option value="">Cargando...</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Fecha Ingreso</label>
                        <input type="datetime-local" id="fecha_ingreso" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Fecha Salida</label>
                        <input type="datetime-local" id="fecha_salida" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Tiempo (min)</label>
                        <input type="number" step="1" id="tiempo" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Temperatura</label>
                        <input type="text" id="temperatura" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">pH</label>
                        <input type="text" id="ph" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">RB</label>
                        <input type="text" id="rb" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="fw-bold">Descripción</label>
                        <textarea id="descripcion" class="form-control" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="guardarLavanderia()">Guardar</button>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
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
                $('#order_trabajo_id').val('');
                $('#producto_id').val('');
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
                cargarProductos();
                cargarTiposProceso();
                $('#modalLavanderia').modal('show');
            });
        }

        function cargarProductos() {
            $.get("{{ route('procesos.listaProductos') }}", function (data) {
                let select = $("#producto_id");
                select.empty();
                select.append('<option value="">Seleccione...</option>');
                data.forEach(item => select.append(`<option value="${item.id}">${item.nombre}</option>`));
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
                    let errors = xhr.responseJSON.errors;
                    let mensaje = '';
                    for (let key in errors) mensaje += errors[key] + '\n';
                    Swal.fire('Error', mensaje, 'error');
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
    </script>
@endsection