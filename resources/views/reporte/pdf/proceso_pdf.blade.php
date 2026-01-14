<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>FICHA DE PROCESO</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        .recibo {
            width: 95%;
            margin: auto;
            padding: 10px;
        }

        .titulo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }

        .subtitulo {
            text-align: center;
            font-size: 12px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 3px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .info-ot td {
            border: none;
            text-align: left;
            padding: 2px;
        }
    </style>
</head>

<body>
    <div class="recibo">
        <div class="titulo">FICHA DE PROCESO</div>
        <div class="subtitulo">Fecha de impresión: {{ $fechaImpresion }}</div>

        <!-- Información general OT -->
        <table class="info-ot">
            <tr>
                <td><strong>OT:</strong> {{ $ot->nro_ot }}</td>
                <td><strong>Cliente:</strong>
                    {{ $cliente->codigo ?? 'No encontrado' }} - {{ $cliente->nombre ?? 'No encontrado' }}
                </td>
                <td><strong>Total Prendas:</strong> {{ number_format($totalPrendas, 2) }}</td>
                <td><strong>Total Peso:</strong> {{ number_format($totalPeso, 2) }} kg</td>
            </tr>
        </table>

        <!-- Detalles de prendas -->
        <table>
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Prenda</th>
                    <th>Cantidad</th>
                    <th>Peso</th>
                    <th>Detalle</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ot->detalles as $key => $detalle)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $detalle->prenda->nombre ?? 'N/A' }}</td>
                        <td>{{ $detalle->cantidad ?? 0 }}</td>
                        <td>{{ $detalle->peso ?? 0 }}</td>
                        <td>
                            {{ $detalle->nombre_tela->nombre ?? '' }} /
                            {{ $detalle->prelavado->nombre ?? '' }} /
                            {{ $detalle->focalizado->nombre ?? '' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tabla de procesos -->
        <table>
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Ciclo</th>
                    <th>Cant (gramos)</th>
                    <th>Producto</th>
                    <th>% gr/litro</th>
                    <th>Tiempo</th>
                    <th>TEMP(ºC)</th>
                    <th>pH</th>
                    <th>R:B</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ot->procesos as $index => $proceso)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $proceso->tipoProceso->nombre ?? 'N/A' }}</td>
                        <td>{{ $proceso->cantida ?? 0 }}</td>
                        <td>{{ $proceso->producto->nombre ?? 'N/A' }}</td>
                        <td>{{ $proceso->gr_litro ?? 0 }}</td>
                        <td>{{ $proceso->tiempo ?? 0 }}</td>
                        <td>{{ $proceso->temperatura ?? '' }}</td>
                        <td>{{ $proceso->ph ?? '' }}</td>
                        <td>{{ $proceso->rb ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>