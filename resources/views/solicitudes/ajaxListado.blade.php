<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_aprobacion">

        <thead>
            <tr>
                <th>Fac/Orden Recepcion</th>
                <th>OT</th>
                <th>Cantidad de solicitudes</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            {{-- @dd($ots) --}}
            @foreach($ots as $otId => $sols)
                {{-- @dd($sols, $otId) --}}
                <tr>
                    <td></td>
                    <td>{{ $otId }}</td>
                    <td>{{ count($sols) }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" onclick="abrirModalOT({{ $otId }})">
                            Aprobar OT
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <!--end::Table-->
</div>

<script>
    $(document).ready(function () {
        $('#kt_aprobacion').DataTable({
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
