<div style="overflow-x: auto;">

    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_aprobacion">
        <thead>
            <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Facturas</th>
                <th>OTs</th>
                <th>Usuario</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($solicitudes as $solicitud)
                <tr>
                    <td>{{ $solicitud->id }}</td>

                    <td>{{ $solicitud->producto->nombre ?? '-' }}</td>

                    <td>
                        @if(is_array($solicitud->ordenes_trabajo))
                            @foreach($solicitud->ordenes_trabajo as $item)
                                <span class="badge bg-success">
                                    {{ $item['nro_factura'] ?? 'S/N' }}
                                </span>
                            @endforeach
                        @endif
                    </td>

                    <!-- <td>
                                @if(is_array($solicitud->ordenes_trabajo))
                                    @foreach($solicitud->ordenes_trabajo as $item)
                                        @foreach($item['ots'] ?? [] as $ot)
                                            <span class="badge bg-secondary">
                                                {{ $ot }}
                                            </span>
                                        @endforeach
                                    @endforeach
                                @endif
                            </td> -->

                    <td>
                        @foreach($solicitud->nros_ot as $nro_ot)
                            <span class="badge bg-primary">
                                {{ $nro_ot }}
                            </span>
                        @endforeach
                    </td>



                    <td>{{ $solicitud->usuarioCreador->name ?? '-' }}</td>

                    <td>{{ $solicitud->created_at->format('d/m/Y') }}</td>

                    <td>
                        @php
                            $estado = $solicitud->estado;
                            $clase = match ($estado) {
                                'APROBADO' => 'bg-success',
                                'RECHAZADO' => 'bg-danger',
                                'EN PROCESO' => 'bg-warning text-dark',
                                default => 'bg-secondary'
                            };
                        @endphp

                        <span class="badge {{ $clase }}">
                            {{ $estado }}
                        </span>
                    </td>

                    <td>
                        <button class="btn btn-icon btn-sm btn-info" title="Ver Detalle"
                            onclick="verDetalleSolicitud({{ $solicitud->id }})">
                            <i class="fa fa-eye"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-danger">
                        No hay solicitudes
                    </td>
                </tr>
            @endforelse

        </tbody>
    </table>

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

            responsive: true
        });


    });
</script>