{{-- <!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CUENTAS POR COBRAR</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            font-size: 10px;
        }

        .recibo {
            width: 95%;
            height: 5.3in;
            box-sizing: border-box;
        }

        .titulo {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .contenido {
            font-size: 14px;
            line-height: 1.5;
        }

        p {
            margin: 4px 0;
        }

        #tabla {
            width: 100%;
        }

        .text-left {
            text-align: left;
        }

        .table {
            /* width: 100%;
            border-collapse: collapse;
            margin-top: 10px; */

            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .table th,
        .table td {
            /* border: 1px solid #444;
            padding: 2px;
            text-align: center; */
            border: 1px solid #444;
            padding: 2px;
            text-align: center;
            vertical-align: middle;
            word-wrap: break-word;
            overflow-wrap: break-word;
            page-break-inside: avoid;
        }

        .table th {
            background-color: #f0f0f0;
        }

        .fondo1Cabecera {
            background-color: #bbaaaa;
        }

        .fondo1Cuerpo {
            background-color: #d4d2d259;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }
    </style>
</head>

<body>
    <div class="recibo">
        <div class="titulo">CUENTAS POR COBRAR</div>
        <table id="tabla">
            <tr>
                <td><strong>Cliente:</strong></td>
                <td class="text-left">{{ $cliente->nombres . " " . $cliente->ap_materno . " " . $cliente->ap_paterno }}</td>
                <td><strong>Fecha:</strong></td>
                <td class="text-left">{{ date('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Direccion:</strong></td>
                <td class="text-left">{{ $cliente->direccion }}</td>
                <td><strong>Celular:</strong></td>
                <td class="text-left">{{ $cliente->celular }}</td>
            </tr>
        </table>
        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>N</th>
                    <th>FECHA</th>
                    <th>Nro RECIBO</th>
                    <th>CANTIDAD</th>
                    <th>DETALLE</th>
                    <th>PRE. UNI.</th>
                    <th>MONTO</th>
                    <th>DESC.</th>
                    <th>PAGADO</th>
                    <th>SALDO</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalServicio = 0;
                    $totalPagado = 0;
                    $totalDeuda = 0;
                @endphp
                @foreach ($facturas as $key => $factura)
                    @php

                        $pagoFactura = $factura->pagos->sum('monto');
                        $saldoFactura = ($factura->total - $factura->descuento_adicional) - $pagoFactura;

                        $ordenTrabajos = $factura->ordenTrabajos;
                        $totalServicio = $totalServicio + $factura->total;
                        $totalPagado = $totalPagado + $pagoFactura;
                        $totalDeuda = $totalDeuda + $saldoFactura;
                    @endphp
                    <tr class="fondo1Cabecera">
                        <td rowspan="{{count($ordenTrabajos) + 1}}">{{ $key + 1  }}</td>
                        <td rowspan="{{count($ordenTrabajos) + 1}}">{{ $factura->fecha }}</td>
                        <td>{{ sprintf('%06d', $factura->numero_factura) }}</td>
                        <td>{{ $ordenTrabajos->sum('cantidad') }}</td>
                        <td></td>
                        <td></td>
                        <td>{{ number_format($factura->total, 2) }}</td>
                        <td rowspan="{{count($ordenTrabajos) + 1}}">{{ number_format($factura->descuento_adicional, 2) }}
                        </td>
                        <td rowspan="{{count($ordenTrabajos) + 1}}">{{ number_format($pagoFactura, 2) }}</td>
                        <td rowspan="{{count($ordenTrabajos) + 1}}">{{ number_format($saldoFactura, 2) }}</td>
                    </tr>
                    @foreach ($ordenTrabajos as $ordenTrabajo)
                        <tr class="fondo1Cuerpo">
                            <td>
                                @if ($ordenTrabajo->tipo == "ORDEN_TRABAJO")
                                    OT: {{ $ordenTrabajo->nro_ot }}
                                @elseif($ordenTrabajo->tipo == "OJAL")
                                    OJAL: {{ $ordenTrabajo->ordenTrabajoSuperior->nro_ot }}
                                @elseif($ordenTrabajo->tipo == "LASER")
                                    LASER: {{ $ordenTrabajo->ordenTrabajoSuperior->nro_ot }}
                                @endif
                            </td>
                            <td>{{ $ordenTrabajo->cantidad }}</td>
                            <td>
                                @if ($ordenTrabajo->tipo == "ORDEN_TRABAJO")
                                    {{ $ordenTrabajo->prenda?->nombre }} ;
                                    [Cant:{{ (int) $ordenTrabajo->cantidad }}] ;
                                    [Peso:{{ $ordenTrabajo->peso }}] ;
                                    [Ojales:{{ (int) $ordenTrabajo->numero_ojales }}/{{ (int) $ordenTrabajo->cantidad }}] ;
                                    @if ($ordenTrabajo->prelavado)
                                        [Pre-Lavado:{{ $ordenTrabajo->prelavado?->nombre }}] ;
                                    @endif
                                    @if ($ordenTrabajo->nevado)
                                        [Nevado:{{ $ordenTrabajo->nevado?->nombre }}] ;
                                    @endif
                                    @if ($ordenTrabajo->focalizado)
                                        [Focalizado:{{ $ordenTrabajo->focalizado?->nombre }}] ;
                                    @endif
                                    @if ($ordenTrabajo->tipoTela)
                                        [Tipo Tela:{{ $ordenTrabajo->tipoTela?->nombre }}] ;
                                    @endif
                                    @if ($ordenTrabajo->colorTela)
                                        [Color Tela:{{ $ordenTrabajo->colorTela?->nombre }}] ;
                                    @endif
                                    @if ($ordenTrabajo->caracteristicaTela)
                                        [Caracteristica Tela:{{ $ordenTrabajo->caracteristicaTela?->nombre }}]
                                    @endif
                                    @if ($ordenTrabajo->talla)
                                        [Talla:{{ $ordenTrabajo->talla }}]
                                    @endif
                                @endif
                            </td>
                            <td>{{ $ordenTrabajo->precio }}</td>
                            <td>{{ $ordenTrabajo->subtotal }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td style="text-align: right" colspan="7"><strong>TOTAL SERVICIO: </strong></td>
                    <td style="text-align: left" colspan="3">{{ number_format($totalServicio, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right" colspan="7"><strong>TOTAL PAGADO: </strong></td>
                    <td style="text-align: left" colspan="3">{{ number_format($totalPagado, 2) }}</td>
                </tr>
                <tr>
                    <td style="text-align: right" colspan="7"><strong>TOTAL SERVICIO: </strong></td>
                    <td style="text-align: left" colspan="3">{{ number_format($totalDeuda, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html> --}}


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CUENTAS POR COBRAR</title>

    <style>
        @page {
            margin: 10px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
        }

        .recibo {
            width: 100%;
            box-sizing: border-box;
        }

        .titulo {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        #tabla {
            width: 100%;
        }

        .text-left {
            text-align: left;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .table th,
        .table td {
            border: 1px solid #444;
            padding: 3px;
            font-size: 9px;
            vertical-align: top;
            word-wrap: break-word;
        }

        .table th {
            background: #f0f0f0;
        }

        .fondo1Cabecera {
            background: #bbaaaa;
        }

        .fondo1Cuerpo {
            background: #d4d2d259;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-row-group;
        }

        .detalle {
            width: 40%;
            text-align: left;
        }
    </style>
</head>

<body>

    <div class="recibo">

        <div class="titulo">
            CUENTAS POR COBRAR
        </div>

        <table id="tabla">

            <tr>
                <td><strong>Cliente:</strong></td>

                <td class="text-left">
                    {{ $cliente->nombres." ".$cliente->ap_materno." ".$cliente->ap_paterno }}
                </td>

                <td><strong>Fecha:</strong></td>

                <td class="text-left">
                    {{ date('d/m/Y') }}
                </td>
            </tr>

            <tr>
                <td><strong>Direccion:</strong></td>

                <td class="text-left">
                    {{ $cliente->direccion }}
                </td>

                <td><strong>Celular:</strong></td>

                <td class="text-left">
                    {{ $cliente->celular }}
                </td>

            </tr>

        </table>

        <hr>

        <table class="table">

            <thead>

                <tr>
                    <th width="3%">N</th>
                    <th width="10%">FECHA</th>
                    <th width="10%">RECIBO</th>
                    <th width="7%">CANT.</th>
                    <th width="42%">DETALLE</th>
                    <th width="7%">P.UNI</th>
                    <th width="7%">MONTO</th>
                    <th width="5%">DESC</th>
                    <th width="5%">PAGADO</th>
                    <th width="7%">SALDO</th>
                </tr>

            </thead>

            <tbody>

                @php
                $totalServicio=0;
                $totalPagado=0;
                $totalDeuda=0;
                @endphp

                @foreach($facturas as $key=>$factura)

                @php

                $pagoFactura=$factura->pagos->sum('monto');

                $saldoFactura=
                ($factura->total-$factura->descuento_adicional)
                -$pagoFactura;

                $ordenTrabajos=$factura->ordenTrabajos;

                $totalServicio+=$factura->total;
                $totalPagado+=$pagoFactura;
                $totalDeuda+=$saldoFactura;

                @endphp

                @foreach($ordenTrabajos as $ordenTrabajo)

                <tr class="{{ $loop->first ? 'fondo1Cabecera':'fondo1Cuerpo' }}">

                    <td>
                        @if($loop->first)
                        {{ $key+1 }}
                        @endif
                    </td>

                    <td>
                        @if($loop->first)
                        {{ $factura->fecha }}
                        @endif
                    </td>

                    <td>

                        @if($ordenTrabajo->tipo=="ORDEN_TRABAJO")
                        OT: {{ $ordenTrabajo->nro_ot }}

                        @elseif($ordenTrabajo->tipo=="OJAL")
                        OJAL:
                        {{ $ordenTrabajo->ordenTrabajoSuperior->nro_ot }}

                        @elseif($ordenTrabajo->tipo=="LASER")
                        LASER:
                        {{ $ordenTrabajo->ordenTrabajoSuperior->nro_ot }}
                        @endif

                    </td>

                    <td>
                        {{ $ordenTrabajo->cantidad }}
                    </td>

                    <td class="detalle">

                        @if($ordenTrabajo->tipo=="ORDEN_TRABAJO")

                        {{ $ordenTrabajo->prenda?->nombre }};

                        [Cant:{{(int)$ordenTrabajo->cantidad}}];

                        [Peso:{{$ordenTrabajo->peso}}];

                        [Ojales:{{(int)$ordenTrabajo->numero_ojales}}/{{(int)$ordenTrabajo->cantidad}}];

                        @if($ordenTrabajo->prelavado)
                        [Pre:{{$ordenTrabajo->prelavado?->nombre}}];
                        @endif

                        @if($ordenTrabajo->nevado)
                        [Nev:{{$ordenTrabajo->nevado?->nombre}}];
                        @endif

                        @if($ordenTrabajo->focalizado)
                        [Foc:{{$ordenTrabajo->focalizado?->nombre}}];
                        @endif

                        @if($ordenTrabajo->tipoTela)
                        [Tela:{{$ordenTrabajo->tipoTela?->nombre}}];
                        @endif

                        @if($ordenTrabajo->colorTela)
                        [Color:{{$ordenTrabajo->colorTela?->nombre}}];
                        @endif

                        @if($ordenTrabajo->caracteristicaTela)
                        [Carac:{{$ordenTrabajo->caracteristicaTela?->nombre}}]
                        @endif

                        @endif

                    </td>

                    <td>
                        {{ number_format($ordenTrabajo->precio,2) }}
                    </td>

                    <td>
                        {{ number_format($ordenTrabajo->subtotal,2) }}
                    </td>

                    <td>
                        @if($loop->first)
                        {{ number_format($factura->descuento_adicional,2) }}
                        @endif
                    </td>

                    <td>
                        @if($loop->first)
                        {{ number_format($pagoFactura,2) }}
                        @endif
                    </td>

                    <td>
                        @if($loop->first)
                        {{ number_format($saldoFactura,2) }}
                        @endif
                    </td>

                </tr>

                @endforeach
                @endforeach

            </tbody>

            <tfoot>

                <tr>
                    <td colspan="7" style="text-align:right">
                        <strong>TOTAL SERVICIO:</strong>
                    </td>

                    <td colspan="3">
                        {{ number_format($totalServicio,2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="7" style="text-align:right">
                        <strong>TOTAL PAGADO:</strong>
                    </td>

                    <td colspan="3">
                        {{ number_format($totalPagado,2) }}
                    </td>
                </tr>

                <tr>
                    <td colspan="7" style="text-align:right">
                        <strong>TOTAL DEUDA:</strong>
                    </td>

                    <td colspan="3">
                        {{ number_format($totalDeuda,2) }}
                    </td>
                </tr>

            </tfoot>

        </table>

    </div>

</body>

</html>
