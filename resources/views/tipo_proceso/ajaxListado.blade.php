<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_tipo_proceso">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Nombre</th>
                @canany([
                'tipos_proceso.editar',
                'tipos_proceso.eliminar'
                ])
                <th>Acciones</th>
                @endcanany
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ($tipo_procesos as $tipo_proceso)
                <tr>
                    <td>{{ $tipo_proceso->nombre }}</td>
                    @canany([
                    'tipos_proceso.editar',
                    'tipos_proceso.eliminar'
                    ])
                    <td>
                        @can('tipos_proceso.editar')
                        <button class="btn btn-icon btn-sm btn-warning btn-circle" title="Editar tipo_proceso" onclick="editarTipoProceso({{ json_encode($tipo_proceso) }})"><i class="fa fa-edit"></i></button>
                        @endcan
                        @can('tipos_proceso.eliminar')
                        <button class="btn btn-icon btn-sm btn-danger btn-circle" title="Eliminar tipo_proceso" onclick="eliminarTipoProceso('{{ $tipo_proceso->id }}',  '{{ $tipo_proceso->nombre }}')"><i class="fa fa-trash"></i></button>
                        @endcan
                    </td>
                    @endcanany
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
        $('#kt_table_tipo_proceso').DataTable({
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
