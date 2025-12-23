<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_laser">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Talla</th>
                <th>Cantidad</th>
                <th>Intensidad</th>
                <th>Altura</th>
                <th>Dpi</th>
                <th>Pos. 1</th>
                <th>Pos. 2</th>
                <th>Pos. 3</th>
                <th>Pos. 4</th>
                <th>Pre x Mesa</th>
                <th>Tie. Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ($ordem_trabajos as $ordenTrabajo)
                <tr>
                    <td>{{ $ordenTrabajo->talla }}</td>
                    <td>{{ $ordenTrabajo->cantidad }}</td>
                    <td>{{ $ordenTrabajo->intensidad }}</td>
                    <td>{{ $ordenTrabajo->altura }}</td>
                    <td>{{ $ordenTrabajo->dpi }}</td>
                    <td>{{ $ordenTrabajo->posicion_1 }}</td>
                    <td>{{ $ordenTrabajo->posicion_2 }}</td>
                    <td>{{ $ordenTrabajo->posicion_3 }}</td>
                    <td>{{ $ordenTrabajo->posicion_4 }}</td>
                    <td>{{ $ordenTrabajo->nro_prenda_mesa }}</td>
                    <td>{{ $ordenTrabajo->tiempo }}</td>
                    <td>
                        {{-- <button class="btn btn-icon btn-sm btn-warning btn-circle" title="Editar rol" onclick="editarRol({{ json_encode($rol) }})"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-icon btn-sm btn-danger btn-circle" title="Eliminar rol" onclick="eliminarRol('{{ $rol->id }}',  '{{ $rol->nombre }}')"><i class="fa fa-trash"></i></button> --}}
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
        $('#kt_table_laser').DataTable({
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
