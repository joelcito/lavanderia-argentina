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

<!-- Listado horizontal de maquinarias -->



<div class="d-flex flex-column flex-column-fluid">

    <div id="kt_app_content" class="app-content flex-column-fluid">

        <div id="kt_app_content_container" class="app-container container-xxlg">

            <!-- TÍTULO Y MAQUINARIAS -->
            <h3 class="fw-bold mb-3">Maquinarias Disponibles</h3>

            <div class="d-flex mb-4 overflow-auto">
                @foreach ($maquinarias as $m)
                    <div class="text-center mx-3" style="cursor:pointer;"
                        onclick="modalNuevaLavanderiaConMaquinaria({{ $m->id }})">

                        <div class="fw-bold">{{ ucfirst($m->tipo) }}</div>

                        @if ($m->tipo == 'lavadora')
                            <img src="{{ asset('assets/img/lavadora.jpg') }}" alt="Lavadora" style="width:80px; height:80px;">
                        @else
                            <img src="{{ asset('assets/img/secadora.png') }}" alt="Secadora" style="width:80px; height:80px;">
                        @endif

                    </div>
                @endforeach
            </div>

            <!-- CARD PRINCIPAL -->
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


<!-- ================= MODAL DE REGISTRO ================= -->

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

                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="maquinaria_id" name="maquinaria_id">
                    <div class="col-md-6 mb-3">
                        <label class="fw-bold">Producto</label>
                        <select id="producto_id" class="form-select">
                            <option value="">Cargando...</option>
                        </select>
                    </div>

                    <!-- Tipo de Proceso -->
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
                        <label class="fw-bold">Cantidad (kg)</label>
                        <input type="number" step="0.01" id="cantida" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Porcentaje (%)</label>
                        <input type="number" step="0.01" id="porcentaje" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="fw-bold">Gr/Litro</label>
                        <input type="number" step="0.01" id="gr_litro" class="form-control">
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
            $.ajax({
                url: "{{ route('procesos.ajaxListado') }}",
                method: "POST",

                data: {},

                success: function (resultado) {
                    if (resultado.estado) {
                        $('#table_listado').html(resultado.data.listado);
                    }
                }
            });
        }






        function modalNuevaLavanderiaConMaquinaria(maquinaria_id) {
            // Limpiar campos
            $('#id').val(0);
            $('#order_trabajo_id').val('');
            $('#producto_id').val('');
            $('#tipo_proceso_id').val('');
            $('#fecha_ingreso').val('');
            $('#fecha_salida').val('');
            $('#cantida').val('');
            $('#porcentaje').val('');
            $('#gr_litro').val('');
            $('#tiempo').val('');
            $('#temperatura').val('');
            $('#ph').val('');
            $('#rb').val('');
            $('#descripcion').val('');
            $('#estado').val('PENDIENTE');

            // Heredar id de maquinaria
            $('#maquinaria_id').val(maquinaria_id);

            // Cargar selects de producto y tipo de proceso
            cargarProductos();
            cargarTiposProceso();

            // Abrir modal
            $('#modalLavanderia').modal('show');
        }





        function cargarProductos() {
            $.get("{{ route('procesos.listaProductos') }}", function (data) {
                let select = $("#producto_id");
                select.empty();
                select.append('<option value="">Seleccione...</option>');

                data.forEach(item => {
                    select.append(`<option value="${item.id}">${item.nombre}</option>`);
                });
            });
        }

        // Cargar tipos de proceso
        function cargarTiposProceso() {
            $.get("{{ route('procesos.listaTiposProceso') }}", function (data) {
                let select = $("#tipo_proceso_id");
                select.empty();
                select.append('<option value="">Seleccione...</option>');

                data.forEach(item => {
                    select.append(`<option value="${item.id}">${item.nombre}</option>`);
                });
            });
        }


        function guardarLavanderia() {
            let datos = {
                id: $('#id').val(),
                order_trabajo_id: $('#order_trabajo_id').val(),
                producto_id: $('#producto_id').val(),
                maquinaria_id: $('#maquinaria_id').val(), // ✅ Se hereda
                tipo_proceso_id: $('#tipo_proceso_id').val(),
                fecha_ingreso: $('#fecha_ingreso').val(),
                fecha_salida: $('#fecha_salida').val(),
                cantida: $('#cantida').val(),
                porcentaje: $('#porcentaje').val(),
                gr_litro: $('#gr_litro').val(),
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
                    for (let key in errors) {
                        mensaje += errors[key] + '\n';
                    }
                    Swal.fire('Error', mensaje, 'error');
                }
            });
        }


    </script>
@endsection