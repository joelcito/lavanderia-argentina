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
                    <h3 class="card-title fw-bold mb-0">Listado de Lavandería - Entregas</h3>
                </div>
                <div class="card-body py-4" id="table_listado">
                    <!-- Tabla AJAX -->
                </div>
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

        var otsSeleccionadas = [];

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function () {
            ajaxListado();

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


        function ajaxListado() {
            $.ajax({
                url: "{{ route('entregas.ajaxListado') }}",
                method: "POST",
                data: {},
                success: function (res) {
                    if (res.estado) {
                        $('#table_listado').html(res.data.listado);
                    }
                }
            });
        }


    </script>
@endsection