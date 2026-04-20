<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>CUENTAS POR COBRAR</title>
    <style>
        @page {
            margin: 0;
            /* elimina márgenes del PDF */
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            /* opcional */
            font-size: 10px;
        }

        .recibo {
            width: 95%;
            height: 5.3in;
            /* un poco menos que 5.5 para evitar salto */
            /* border: 1px solid #000; */
            padding: 20px;
            box-sizing: border-box;
            /* background-color:red; */
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
            /* background-color:red; */
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
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #444;
            padding: 2px;
            text-align: center;
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
                                    {{-- {{
                                    $ordenTrabajo->prenda?->nombre."/".$ordenTrabajo->tela?->nombre."/".$ordenTrabajo->prelavado?->nombre."/".$ordenTrabajo->nevado?->nombre."/".$ordenTrabajo->focalizado?->nombre
                                    }} --}}
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


                                    {{-- @if ($ordenTrabajo->con_muestra)
                                    [Con Muestra: SI]
                                    @else
                                    [Con Muestra: NO]
                                    @endif --}}
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

</html>