<div style="overflow-x: auto;">
    <table class="table align-middle table-row-dashed fs-8 gy-2" id="kt_table_facturas">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Sucursal</th>
                <th>Cliente</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Descuento</th>
                <th>Sub Total</th>
                <th>A cuenta</th>
                <th>Fac / Or</th>
                <th>Usuario</th>
                <th>Est. Ven.</th>
                <th>Estado</th>
                <th>Prioridad</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @php
                $montoTotal          = 0;
                $montoTotalDescuento = 0;
                $montoTotalSubTotal  = 0;
                $montoTotalACuneta   = 0;
            @endphp
            @forelse ( $facturas as $fac)
                @php

                    $montoTotal = $montoTotal + $fac->total;
                    $montoTotalDescuento = $montoTotalDescuento + $fac->descuento_adicional;
                    $montoTotalSubTotal = $montoTotalSubTotal + ($fac->total - $fac->descuento_adicional);
                    $montoTotalACuneta = $montoTotalACuneta + $fac->pagos->sum('monto');

                @endphp
                <tr>
                    <td>{{ $fac->sucursal?->nombre }}</td>
                    <td>{{ $fac->nombres . ' ' . $fac->ap_paterno . ' ' . $fac->ap_materno }}</td>
                    <td>{{ $fac->fecha }}</td>
                    <td>{{ number_format($fac->total,2) }}</td>
                    <td>{{ number_format($fac->descuento_adicional, 2) }}</td>
                    <td>
                        <span class="text-warning">
                            {{ number_format($fac->total - $fac->descuento_adicional, 2) }}
                        </span>
                    </td>
                    <td>
                        {{ number_format($fac->pagos->sum('monto'), 2) }}
                    </td>
                    <td>
                        <span class="text-info">
                            {{ sprintf('%06d', $fac->numero_factura) }}
                        </span>
                    </td>
                    <td>
                        {{ $fac->usuarioCreador->nombres . ' ' . $fac->usuarioCreador->ap_paterno }}
                    </td>
                    <td>
                        @if ($fac->estado_pago == "DEUDA")
                            <span class="badge badge-danger">{{ $fac->estado_pago }}</span>
                        @elseif($fac->estado_pago == "PAGADO")
                            <span class="badge badge-success">{{ $fac->estado_pago }}</span>
                        @else
                            {{ $fac->estado_pago }}
                        @endif
                    </td>
                    <td>
                        @if (is_null($fac->estado))
                            <span class="badge badge-success">VIGENTE</span>
                        @elseif($fac->estado == 'Anulado')
                            <span class="badge badge-danger">ANULADO</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge badge-info">{{ $fac->prioridad }}</span>
                    </td>
                    <td>
                        @if (is_null($fac->estado))
                            <a href="{{route('factura.detalle', [$fac->id])}}" class="btn btn-sm btn-icon tamanio_boton btn-info" title="Ver Detelles"><i class="fa fa-eye"></i></a>
                            <button class="btn btn-primary btn-sm btn-icon tamanio_boton" title="Imprime Recibo"
                                onclick="imprimeREcibo('{{ $fac->id }}')"><i
                                    class="fa fa-file-pdf"></i></button>
                            <button class="btn btn-danger btn-sm btn-icon tamanio_boton"
                                onclick="anularRecibo('{{ $fac->id }}', '{{ $fac->numero_factura }}')"><i class="fa fa-trash"></i></button>
                        @endif
                    </td>
                </tr>
            @empty
                <h4 class="text-danger">No hay datos</h4>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><b>TOTAL</b></td>
                <td><b>{{ number_format($montoTotal , 2) }}</b></td>
                <td><b>{{ number_format($montoTotalDescuento , 2) }}</b></td>
                <td><b>{{ number_format($montoTotalSubTotal , 2) }}</b></td>
                <td><b>{{ number_format($montoTotalACuneta , 2) }}</b></td>
                <td colspan="6"></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    $(document).ready(function() {
        $('#kt_table_facturas').DataTable({
            lengthMenu: [10, 25, 50, 100], // Opciones de longitud de página
            // dom: '<"dt-head row"<"col-md-6"l><"col-md-6"f>><"clear">t<"dt-footer row"<"col-md-5"i><"col-md-7"p>>', // Use dom for basic layout
            dom: 't<"dt-footer row"<"col-md-5"i><"col-md-7"p>>', // Use dom for basic layout
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
