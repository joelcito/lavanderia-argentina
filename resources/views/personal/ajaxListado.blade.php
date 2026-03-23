<div style="overflow-x: auto;">



    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

        <!-- lado izquierdo (título opcional) -->
        <div>
            <h5 class="mb-0">Control de Personal</h5>
        </div>

        <!-- lado derecho (botones) -->
        <div class="d-flex gap-2 flex-wrap">
            <button class="btn btn-danger btn-sm btn-reporte-lavador">
                <i class="fa fa-file-pdf"></i> Reporte Lavadores
            </button>

            <button class="btn btn-primary btn-sm">
                Reporte Focalizador
            </button>

            <button class="btn btn-secondary btn-sm">
                Reporte Planchador
            </button>
        </div>

    </div>

    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_prendas">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Rol</th>
                <th>Nombre</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @foreach($usuarios as $user)
                <tr>

                    <td>{{ $user->rol->nombre ?? 'Sin rol' }}</td>
                    <td>{{ $user->nombres }} {{ $user->ap_paterno }} {{ $user->ap_materno }}</td>
                    <!-- <td>
                                                                            <button class="btn btn-primary btn-ver" data-id="{{ $user->id }}">
                                                                                Ver detalle
                                                                            </button>
                                                                        </td> -->
                    <td>
                        <button class="btn btn-info btn-sm btn-config" data-id="{{ $user->id }}">
                            ⚙ Config
                        </button>

                        <button class="btn btn-warning btn-sm btn-asistencia" data-id="{{ $user->id }}">
                            🕒 Asistencia
                        </button>

                        <button class="btn btn-success btn-sm btn-pagos" data-id="{{ $user->id }}">
                            💰 Pagos
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


<script>
    $(document).ready(function () {
        $('#kt_table_prendas').DataTable({
            lengthMenu: [10, 25, 50, 100],
            dom: '<"dt-head row"<"col-md-6"l><"col-md-6"f>><"clear">t<"dt-footer row"<"col-md-5"i><"col-md-7"p>>',
            language: {
                paginate: {
                    first: 'Primero',
                    last: 'Último',
                    next: 'Siguiente',
                    previous: 'Anterior'
                },
                search: 'Buscar:',
                lengthMenu: 'Mostrar _MENU_ registros por página',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                emptyTable: 'No hay datos disponibles'
            },
            order: [],
            //  searching: true,
            responsive: true
        });
    });

    $(document).on('click', '.btn-reporte-lavador', function () {


        $.get('/control-personal/lavador/formulario', function (html) {

            $('#contenido-modal').html(html);
            $('#modalPersonal').modal('show');

        });

    });


</script>