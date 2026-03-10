@if (count($preparaciones) == 0)
<div class="row">
    <div class="col-md-12">
        <button onclick="modalNuevoPreparacion({{ $solicitud_id }})" class="btn btn-success w-100 btn-sm"><i class="fa fa-plus"></i> Nuevo Preparacion</button>
    </div>
</div>
@endif
<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_roles">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Cantidad</th>
                <th>Cantidad Liquido</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @php
                $cantidad = $preparaciones->whereNotNull('preparacion_id')->sum('cantidad');
                // $cantidad_liquido = $preparaciones->whereNull('preparacion_id')->sum('cantidad_liquido');
            @endphp
            @forelse ($preparaciones as $preparacion)
                <tr>
                    <td>{{ $preparacion->cantidad }}</td>
                    <td>{{ $preparacion->cantidad_liquido }}</td>
                    <td>
                        @if ($preparacion->preparacion_id == null)
                            <button title="Separar para Carga" onclick="modalDivicionPreparacionCarga({{ $preparacion->id }}, '{{ $preparacion->cantidad + $preparacion->cantidad_liquido }}', '{{ $cantidad }}')" class="btn btn-dark btn-icon btn-sm"><i class="fa fa-sad-cry"></i></button>
                        @endif
                    </td>
                </tr>
            @empty
                <h4 class="text-danger">No hay datos</h4>
            @endforelse
        </tbody>
    </table>
    <!--end::Table-->
</div>

<script>
    $(document).ready(function() {
        $('#kt_table_roles').DataTable({
            lengthMenu: [10, 25, 50, 100], // Opciones de longitud de página
            dom: '<"dt-head row"<"col-md-6"l><"col-md-6"f>><"clear">t<"dt-footer row"<"col-md-5"i><"col-md-7"p>>', // Use dom for basic layout
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
</script>
