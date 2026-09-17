<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_maquinarias">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Tipo</th>
                <th>Numero</th>
                <th>Descripcion</th>
                <th>Estado</th>
                @canany([
                'maquinarias.editar',
                'maquinarias.eliminar'
                ])
                <th>Acciones</th>
                @endcanany
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ($maquinarias as $maquinaria)
                <tr>
                    <td>{{ $maquinaria->tipo }}</td>
                    <td>{{ $maquinaria->numero }}</td>
                    <td>{{ $maquinaria->descripcion }}</td>
                    <td>
                        <span class="badge
                                @if($maquinaria->estado_maquina == 'DISPONIBLE') badge-success
                                @elseif($maquinaria->estado_maquina == 'EN PROCESO') badge-warning
                                @else badge-danger
                                @endif
                            ">
                            {{ $maquinaria->estado_maquina }}
                        </span>
                    </td>
                    @canany([
                    'maquinarias.editar',
                    'maquinarias.eliminar'
                    ])
                    <td>
                        @can('maquinarias.editar')
                        <button class="btn btn-icon btn-sm btn-warning btn-circle" title="Editar maquinaria"
                            onclick="editarMaquinaria({{ json_encode($maquinaria) }})"><i class="fa fa-edit"></i></button>
                        @endcan
                        @can('maquinarias.eliminar')
                        <button class="btn btn-icon btn-sm btn-danger btn-circle" title="Eliminar maquinaria"
                            onclick="eliminarMaquinaria('{{ $maquinaria->id }}',  '{{ $maquinaria->tipo }}')"><i
                                class="fa fa-trash"></i></button>
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
    $(document).ready(function () {
        $('#kt_table_maquinarias').DataTable({
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
