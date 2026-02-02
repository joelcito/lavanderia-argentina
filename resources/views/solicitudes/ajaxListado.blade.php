<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_aprobacion">

        <thead>
            <tr>
                <th>Fac/Orden Recepcion</th>
                <th>Ordenes de trabajo</th>
                <th>Usuario Solicitante</th>
                {{-- <th>Cantidad de solicitudes</th>--}}
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($facturasSolicitadas as $factura)
                <tr>
                    <td>
                        Factura {{ $factura->numero_factura }}

                    </td>
                    <td>
                        OTs: {{ implode(',', $factura->ots) }}
                    </td>

                    <td>
                        {{ $factura->usuarioCreador->nombres }}
                        {{ $factura->usuarioCreador->ap_paterno }}
                    </td>

                    <td>
                        <button class="btn btn-icon btn-sm btn-info" title="Ver Detalle"
                            onclick="verDetalleSolicitud('{{ $factura->factura_id }}')">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>
            @endforeach



            <!-- @foreach ($facturasSolicitadas as $factura)
                <tr>
                    <td>{{ $factura->numero_factura }}</td>
                    <td>{{ $factura->usuarioCreador->nombres." ".$factura->usuarioCreador->ap_paterno." ".$factura->usuarioCreador->ap_materno }}</td>
                    <td>
                        <button class="btn btn-icon btn-sm btn-info" onclick="verDetalleSolicitud('{{ $factura->factura_id }}', '{{ $factura->numero_factura }}')"><i class="fa fa-eye"></i></button>
                    </td>
                </tr>
            @endforeach -->
            {{-- @foreach($ots as $otId => $sols)
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
            @endforeach --}}
        </tbody>
    </table>
    <!--end::Table-->
</div>

<script>
    $(document).ready(function () {
        $('#kt_aprobacion').DataTable({
            lengthMenu: [10, 25, 50, 100],
            dom: '<"dt-head row"<"col-md-6"l><"col-md-6"f>><"clear">t<"dt-footer row"<"col-md-5"i><"col-md-7"p>>',
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