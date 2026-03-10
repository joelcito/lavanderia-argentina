<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo</title>
    <style>
        @page {
            margin: 0; /* elimina márgenes del PDF */
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff; /* opcional */
            font-size: 10px;
        }

        .recibo {
            width: 95%;
            height: 5.3in; /* un poco menos que 5.5 para evitar salto */
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

        #tabla{
            width: 100%;
        }

        .text-left{
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
    </style>
</head>
<body>
    <div class="recibo">
        <div class="titulo">FICHA DE PROCESO </div>
        <table id="tabla">
            <tr>
                <td><strong>Usuario Impresion:</strong> {{$usuario->nombres." ".$usuario->ap_paterno." ".$usuario->ap_materno}}</td>
                <td class="text-left"></td>
                <td><strong>Fecha Impresion:</strong> {{ date('d/m/Y H:i:s') }}</td>
                <td class="text-left"></td>
            </tr>
        </table>
        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>CLIENTE</th>
                    <th>FACTURA/ORDEN RECEPCION</th>
                    <th>CANTIDAD PRENDAS</th>
                    <th>PESO</th>
                    <th>NRO OT'S</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPrendasTotal = 0;
                    $totalPesoTotal = 0;
                @endphp
                @foreach ( $tipo as $t)
                    @php
                        $factura         = App\Models\Factura::find($t['factura_id']);
                        $cantidadPrendas = 0;
                        $peso            = 0;
                        $nroOt           = "N° | ";
                        $arrayOts        = array();

                        foreach ($t['ots'] as $key => $ot) {
                            $ordenTrabajo = App\Models\Order_trabajo::find($ot);
                            $cantidadPrendas+=$ordenTrabajo->cantidad;
                            $peso+=$ordenTrabajo->peso;

                            if(!in_array($ordenTrabajo->nro_ot , $arrayOts)){
                                $arrayOts[] = $ordenTrabajo->nro_ot;
                                $nroOt .= $ordenTrabajo->nro_ot." | ";
                            }
                        }

                        $totalPrendasTotal+=$cantidadPrendas;
                        $totalPesoTotal+=$peso;

                    @endphp
                    <tr>
                        <td>
                            {{ $factura->cliente->nombres." ".$factura->cliente->ap_paterno." ".$factura->cliente->ap_materno }}
                        </td>
                        <td>{{ $factura->numero_factura }}</td>
                        <td>{{ $cantidadPrendas }}</td>
                        <td>{{ $peso }}</td>
                        <td>{{ $nroOt }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"><strong>TOTAL</strong></td>
                    <td>{{ $totalPrendasTotal }}</td>
                    <td>{{ $totalPesoTotal }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
        <hr>
        <div class="titulo">PROCESOS </div>
        <table class="table">
            <thead>
                <tr>
                    <th>MAQUINA</th>
                    <th>PROCESO</th>
                    <th>PRODUCTO</th>
                    <th>FECHA INGRESO</th>
                    <th>TIEMPO</th>
                    <th>FECHA SALIDA</th>
                    <th>CANTIDAD</th>
                    <th>PORCENTAJE</th>
                    <th>TEMP</th>
                    <th>PH</th>
                    <th>RB</th>
                    <th>DESCRIPCION</th>
                </tr>
            </thead>
            <tbody>
                {{-- @dd($procesos, count($procesos)); --}}
                @foreach ( $procesos as $proceso)
                    <tr>
                        <td>
                            @if ($proceso->tipoProceso?->id != 4)
                                {{ $proceso->maquinaria?->tipo." N: ".$proceso->maquinaria?->numero }}
                            @endif
                        </td>
                        <td>{{ $proceso->tipoProceso?->nombre }}</td>
                        <td>
                            @if ($proceso->tipoProceso?->id != 4)
                                {{ $proceso->producto?->nombre }}
                            @else
                                {{ $proceso->solicitud_id }}
                            @endif
                        </td>
                        <td>{{ $proceso->fecha_ingreso }}</td>
                        <td>{{ $proceso->tiempo }}</td>
                        <td>{{ $proceso->fecha_salida }}</td>
                        <td>
                            @if ($proceso->tipoProceso?->id != 4)
                                {{ $proceso->cantidad }}
                            @endif
                        </td>
                        <td>
                            @if ($proceso->tipoProceso?->id != 4)
                                {{ $proceso->porcentaje }}
                            @endif
                        </td>
                        <td>{{ $proceso->temperatura }}</td>
                        <td>{{ $proceso->ph }}</td>
                        <td>{{ $proceso->rb }}</td>
                        <td>{{ $proceso->descripcion }}</td>
                    </tr>
                @endforeach
            </tbody>
            {{-- <tfoot>
                <tr>
                    <td colspan="2"><strong>TOTAL</strong></td>
                    <td>{{ $totalPrendasTotal }}</td>
                    <td>{{ $totalPesoTotal }}</td>
                    <td></td>
                </tr>
            </tfoot> --}}
        </table>
    </div>
</body>
</html>

