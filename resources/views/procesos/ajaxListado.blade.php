<div style="overflow-x: auto;">



    <!--begin::Table-->
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>

                <th>Producto</th>
                <th>Maquinaria</th>
                <th>Tipo Proceso</th>
                <th>Fecha Ingreso</th>
                <th>Fecha Salida</th>
                <th>Cantidad</th>

                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($procesos as $p)
                <tr>
                    <td>{{ $p->id }}</td>

                    <td>{{ $p->producto->nombre ?? '-' }}</td>
                    <td>{{ $p->maquinaria->tipo ?? '-' }}</td>
                    <td>{{ $p->tipoProceso->nombre ?? '-' }}</td>
                    <td>{{ $p->fecha_ingreso ?? '-' }}</td>
                    <td>{{ $p->fecha_salida ?? '-' }}</td>
                    <td>{{ $p->cantida ?? '-' }}</td>

                    <td>{{ $p->estado ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center">No hay procesos registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <!--end::Table-->
</div>

<script>
    $(document).ready(function () {
        $('#kt_table_usuarios').DataTable({
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