<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_roles">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Prelavado</th>
                <th>Nevado</th>
                <th>Focalizado</th>
                <th>Cantidad Prendas</th>
                <th>Peso</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ($cotizaciones as $cotizacion)
                <tr>
                    <td>{{ $cotizacion->cliente->nombres." ".$cotizacion->cliente->ap_paterno." ".$cotizacion->cliente->ap_materno }}</td>
                    <td>{{ $cotizacion->created_at }}</td>
                    <td>{{ $cotizacion->prelavado?->nombre }}</td>
                    <td>{{ $cotizacion->nevado?->nombre }}</td>
                    <td>{{ $cotizacion->focalizado?->nombre }}</td>
                    <td>{{ $cotizacion->cantidad_prenda }}</td>
                    <td>{{ $cotizacion->peso_kg }}</td>
                    <td class="text-center">

                        <button class="btn btn-icon btn-warning btn-sm" onclick='editarCotizacion(@json($cotizacion))'><i class="fa fa-edit"></i></button>

                        <button type="button" class="btn btn-sm btn-danger btn-icon" onclick="reportePdf({{ $cotizacion->id }})"
                            title="Exportar PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>

                        <button type="button" class="btn btn-sm btn-success btn-icon" onclick="reporteExcel({{ $cotizacion->id }})"
                            title="Exportar Excel">
                            <i class="fas fa-file-excel"></i>
                        </button>
                        <button class="btn btn-danger btn-icon btn-sm" onclick="eliminarCotizacion('{{$cotizacion->id}}')"><i class="fa fa-trash"></i></button>
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
