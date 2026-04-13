<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-7 gy-6" id="kt_table_roles">
        <thead>
            <tr class="text-start text-muted fw-bold fs-8 text-uppercase gs-0">
                <th>Cantidad</th>
                <th>Ojales</th>
                <th>Prenda</th>
                <th>Pre. Lavado</th>
                <th>Nevado</th>
                <th>Focalizado</th>
                <th>Co. Tela</th>
                <th>Ca. Tela</th>
                <th>Peso</th>
                <th>Nro. OT</th>
                <th>Ultimo proceso</th>
                <th>Estado</th>
                {{-- <th>Actions</th> --}}
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ($ordem_trabajos as $ordenTrabajo)
                <tr>
                    <td>{{ $ordenTrabajo->cantidad }}</td>
                    <td>{{ $ordenTrabajo->numero_ojales }}</td>
                    <td>{{ $ordenTrabajo->prenda?->nombre }}</td>
                    <td>{{ $ordenTrabajo->prelavado?->nombre }}</td>
                    <td>{{ $ordenTrabajo->nevado?->nombre }}</td>
                    <td>{{ $ordenTrabajo->focalizado?->nombre }}</td>
                    <td>{{ $ordenTrabajo->colorTela?->nombre }}</td>
                    <td>{{ $ordenTrabajo->caracteristicaTela?->nombre }}</td>
                    <td>{{ $ordenTrabajo->preso }}</td>
                    <td>{{ $ordenTrabajo->nro_ot }}</td>
                    <td><span class="badge badge-primary">
                            {{ $ordenTrabajo->ultimo_proceso }}
                        </span></td>
                    <td>
                        @if ($ordenTrabajo->estado == "RECEPCIONADO")
                            <span class="badge badge-info">{{ $ordenTrabajo->estado }}</span>
                        @elseif($ordenTrabajo->estado == "TRABAJANDO" || $ordenTrabajo->estado == "EN PROCESO")
                            <span class="badge badge-warning">{{ $ordenTrabajo->estado }}</span>
                        @elseif($ordenTrabajo->estado == "FINALIZADO")
                            <span class="badge badge-success">{{ $ordenTrabajo->estado }}</span>
                        @elseif($ordenTrabajo->estado == "ENTREGADO")
                            <span class="badge badge-dark">{{ $ordenTrabajo->estado }}</span>
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
    $(document).ready(function () {
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