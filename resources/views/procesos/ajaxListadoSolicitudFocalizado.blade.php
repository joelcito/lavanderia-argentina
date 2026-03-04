<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_roles">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Porcentaje</th>
                <th>Estado</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ($solicitudes as $solicitud)
                <tr>
                    <td>{{ $solicitud->producto->nombre }}</td>
                    <td>{{ $solicitud->cantidad }}</td>
                    <td>{{ $solicitud->porcentaje }}</td>
                    <td>
                        @if ($solicitud->estado == "EN PROCESO")
                            <span class="badge badge-warning text-dark">{{ $solicitud->estado }}</span>
                        @elseif( $solicitud->estado == "APROBADO")
                            <span class="badge badge-success text-dark">{{ $solicitud->estado }}</span>
                        @else
                            <span class="badge badge-white text-dark">{{ $solicitud->estado }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($solicitud->estado == "APROBADO")
                            <button title="Preparacion" class="btn btn-icon btn-sm btn-success"><i class="fa fa-cogs"></i></button>
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
