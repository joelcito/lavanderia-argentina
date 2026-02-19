<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_procesos_trabajando">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Maquinaria</th>
                {{-- <th>Solicitud</th> --}}
                <th>Producto</th>
                <th>Tipo Proceso</th>
                <th>Fecha Ingreso</th>
                <th>Fecha Salida</th>
                <th>Tiempo</th>
                <th>Temperatura</th>
                <th>PH</th>
                <th>RB</th>
                <th>Descripcion</th>
                <th>Estado</th>
                <th></th>
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ($procesos as $proceso)
                <tr>
                    <td>{{ $proceso->maquinaria->tipo }}</td>
                    {{-- <td>{{ $proceso->maquinaria->nombre }}</td> --}}
                    <td>{{ $proceso->producto->nombre }}</td>
                    <td>{{ $proceso->tipoProceso->nombre }}</td>
                    <td>{{ $proceso->fecha_ingreso }}</td>
                    <td>{{ $proceso->fecha_salida }}</td>
                    <td>{{ $proceso->tiempo }}</td>
                    <td>{{ $proceso->temperatura }}</td>
                    <td>{{ $proceso->ph }}</td>
                    <td>{{ $proceso->rb }}</td>
                    <td>{{ $proceso->descripcion }}</td>
                    <td>{{ $proceso->estado }}</td>
                    <td>
                        {{-- <button class="btn btn-icon btn-circle btn-danger btn-sm"><i class="fa fa-stop"></i></button> --}}
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
        $('#kt_table_procesos_trabajando').DataTable({
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
