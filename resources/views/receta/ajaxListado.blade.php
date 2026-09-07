<div style="overflow-x: auto;">

    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_recetas">

        <thead>

            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">

                <th>#</th>

                <th>Nombre</th>

                <th>Tipo Tela</th>

                <th>Color</th>

                <th>Nombre Tela</th>

                <th>Prelavado</th>

                <th>Focalizado</th>

                <th>Nevado</th>

                <th>Proceso</th>

                <th>Detalles</th>

                <th>Descripción</th>

                <th>Acciones</th>

            </tr>

        </thead>


        <tbody class="text-gray-600 fw-semibold">

            @foreach ($recetas as $receta)

            <tr>

                <td>
                    {{ $receta->id }}
                </td>

                <td>
                    {{ $receta->nombre }}
                </td>

                <td>
                    {{ $receta->tipoTela?->nombre ?? '-' }}
                </td>

                <td>
                    {{ $receta->colorTela?->nombre ?? '-' }}
                </td>

                <td>
                    {{ $receta->nombreTela?->nombre ?? '-' }}
                </td>

                <td>
                    {{ $receta->prelavado?->nombre ?? '-' }}
                </td>

                <td>
                    {{ $receta->focalizado?->nombre ?? '-' }}
                </td>

                <td>
                    {{ $receta->nevado?->nombre ?? '-' }}
                </td>

                <td>
                    {{ $receta->tipoProceso?->nombre ?? '-' }}
                </td>

                <td>
                    <span class="badge badge-light-primary">
                        {{ $receta->detalles->count() }}
                    </span>
                </td>

                <td>
                    {{ \Illuminate\Support\Str::limit(
                    $receta->descripcion,
                    40
                    ) }}
                </td>

                <td>

                    <button type="button" class="btn btn-icon btn-sm btn-warning btn-circle" title="Editar receta"
                        onclick='editarReceta(@json($receta))'>

                        <i class="fa fa-edit"></i>

                    </button>

                    <button type="button" class="btn btn-icon btn-sm btn-danger btn-circle" title="Imprimir receta en PDF"
                        onclick="imprimirReceta('{{ $receta->id }}')">

                        <i class="fa fa-file-pdf"></i>

                    </button>

                    <button type="button" class="btn btn-icon btn-sm btn-danger btn-circle" title="Eliminar receta"
                        onclick="eliminarReceta('{{ $receta->id }}')">

                        <i class="fa fa-trash"></i>

                    </button>

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</div>


<script>
    $(document).ready(function() {

    $('#kt_table_recetas').DataTable({

        lengthMenu: [
            10,
            25,
            50,
            100
        ],

        dom:
            '<"dt-head row"' +
                '<"col-md-6"l>' +
                '<"col-md-6"f>' +
            '>' +
            '<"clear">' +
            't' +
            '<"dt-footer row"' +
                '<"col-md-5"i>' +
                '<"col-md-7"p>' +
            '>',


        language: {

            paginate: {

                first:
                    'Primero',

                last:
                    'Último',

                next:
                    'Siguiente',

                previous:
                    'Anterior'

            },

            search:
                'Buscar:',

            lengthMenu:
                'Mostrar _MENU_ registros por página',

            info:
                'Mostrando _START_ a _END_ de _TOTAL_ registros',

            emptyTable:
                'No hay datos disponibles',

            zeroRecords:
                'No se encontraron registros'

        },

        order: [],

        responsive: false,

        scrollX: true

    });

});

</script>
