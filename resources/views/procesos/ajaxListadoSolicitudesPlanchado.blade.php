{{-- @dd($solicitudArray) --}}
<div style="overflow-x: auto;">
    <table class="table table-bordered table-hover" id="kt_table_color_tela">
        <thead>
            <tr>
                <th>Agrupados para proceso</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            {{-- @dd($solicitudArray) --}}
            @forelse ($solicitudArray as $key => $solcitudAgrupado)
                @php
                    $solicitud_id = $key;

                    $proceso = App\Models\Proceso::select('estado')
                        ->where('solicitud_id', $solicitud_id)
                        ->where('tipo_proceso_id', 20) // PLACNHADO
                        ->groupBy('solicitud_id', 'estado')
                        ->first();
                @endphp
                @if ($proceso && $proceso->estado == "TRABAJANDO")
                    <tr>
                        <td>{{ $solcitudAgrupado }}</td>
                        <td>
                            <span class="badge badge-warning">{{ $proceso->estado }}</span>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="abrirModalPlanchado('{{ $solicitud_id }}')">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button title="Terminar Proceso Planchado" class="btn btn-dark btn-icon btn-sm"
                                onclick="finalizarPlanchado('{{ $solicitud_id }}')"><i class="fa fa-up-down"></i></button>


                        </td>
                    </tr>
                @endif
            @empty
                <span class="text-danger">No hay OTs registradas</span>
            @endforelse
        </tbody>
    </table>
</div>


<script>
    $(document).ready(function () {
        $('#kt_table_color_tela').DataTable({
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