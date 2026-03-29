<div style="overflow-x: auto;">

    <div class="d-flex mb-3 flex-wrap gap-2 justify-content-end">
        <div class="d-flex gap-2 flex-wrap ">
            <button class="btn btn-danger btn-sm btn-reporte-lavador">
                <i class="fa fa-file-pdf"></i> Reporte Lavadores
            </button>
            <button class="btn btn-primary btn-sm">
                <i class="fa fa-file-pdf"></i> Reporte Focalizador
            </button>
            <button class="btn btn-secondary btn-sm">
                <i class="fa fa-file-pdf"></i> Reporte Planchador
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
                    <td>

                        @if($user->rol_id == 3)
                            <button class="btn btn-info btn-sm btn-config" data-id="{{ $user->id }}">Config</button>
                            <button class="btn btn-warning btn-sm btn-asistencia" data-id="{{ $user->id }}"> Asistencia</button>
                            <button class="btn btn-success btn-sm btn-pagos" data-id="{{ $user->id }}">Pagos</button>
                        @endif
                        @if($user->rol_id == 6)
                            <button class="btn btn-success btn-sm btn-produccion" data-id="{{ $user->id }}"
                                data-tipo="focalizador">
                                Pago Focalizador
                            </button>
                        @endif
                        @if($user->rol_id == 5)
                            <button class="btn btn-dark btn-sm btn-produccion" data-id="{{ $user->id }}" data-tipo="planchador">
                                Pago Planchador
                            </button>
                        @endif
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
            searching: true,
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