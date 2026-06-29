<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>ESTRUCTURA COSTOS</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        .titulo {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
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

        .producto {
            font-weight: bold;
            font-size: 12px;
            margin-top: 10px;
        }
    </style>
</head>

<body>

    <div class="titulo">
        COTIZACION # {{ $cotizacion->id }}
    </div>

    <div class="subtitulo">
        Cliente: <strong>{{ $cotizacion->cliente->nombres." ".$cotizacion->cliente->ap_paterno." ".$cotizacion->cliente->ap_materno }}</strong><br>
        Fecha: <strong>{{ $cotizacion->created_at }}</strong>
        Cantidad: <strong>{{ intval($cotizacion->cantidad_prenda) }}</strong>
        Prelavado: <strong>{{ $cotizacion->prelavado->nombre }}</strong>
        Nevado: <strong>{{ $cotizacion->nevado->nombre }}</strong>
        Focalizado: <strong>{{ $cotizacion->focalizado->nombre }}</strong>
    </div>

    <table>
        <thead>
            <tr>
                <th>Proceso</th>
                <th>Producto</th>
                <th>Porcentaje</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            @php
                $tipoProcesos = $cotizacion->detalles->groupBy('tipo_proceso_id');
            @endphp

            @foreach ($tipoProcesos as $tipoProceso)
                @php
                $cat = count($tipoProceso);
                $first = true;
                @endphp

                @foreach ($tipoProceso as $detalle)
                <tr>
                    @if ($first)
                    <td rowspan="{{ $cat }}">
                        {{ $detalle->proceso->nombre }}
                    </td>
                    @php $first = false; @endphp
                    @endif

                    <td>{{ $detalle->producto?->nombre }}</td>
                    <td>{{ $detalle->porcentaje }}</td>
                    <td>{{ $detalle->cantidad }}</td>
                    <td>{{ $detalle->total }}</td>
                </tr>
                @endforeach
            @endforeach
            <tr>
                <td>
                    <strong>Mano de Obra: </strong>{{ $cotizacion->mano_obra }}
                </td>
                <td>
                    <strong>Servicio Basico: </strong>{{ $cotizacion->servicio_basico }}
                </td>
                <td>
                    <strong>Mantenimiento: </strong>{{ $cotizacion->mantenimiento }}
                </td>
                <td colspan="2">
                    <strong>Interes Bancario: </strong>{{ $cotizacion->interes_bancario }}
                </td>
            </tr>

            <tr>
                <td>
                    <strong>COSTO: </strong>
                </td>
                <td>
                    <strong>{{ $cotizacion->costo_s1 }}</strong>
                </td>
                <td>
                    <strong>{{ $cotizacion->costo_s2 }}</strong>
                </td>
                <td colspan="2">
                    <strong>{{ $cotizacion->costo_s3 }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>PRECIO: </strong>
                </td>
                <td>
                    <strong>{{ $cotizacion->precio_s1 }}</strong>
                </td>
                <td>
                    <strong>{{ $cotizacion->precio_s2 }}</strong>
                </td>
                <td colspan="2">
                    <strong>{{ $cotizacion->precio_s3 }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>UTILIDAD: </strong>
                </td>
                <td>
                    <strong>{{ $cotizacion->utilidad_s1 }}</strong>
                </td>
                <td>
                    <strong>{{ $cotizacion->utilidad_s2 }}</strong>
                </td>
                <td colspan="2">
                    <strong>{{ $cotizacion->utilidad_s3 }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>% GANANCIA: </strong>
                </td>
                <td>
                    <strong>{{ $cotizacion->porcentaje_ganancia_s1 }}</strong>
                </td>
                <td>
                    <strong>{{ $cotizacion->porcentaje_ganancia_s2 }}</strong>
                </td>
                <td colspan="2">
                    <strong>{{ $cotizacion->porcentaje_ganancia_s3 }}</strong>
                </td>
            </tr>
            <tr>
                <td>
                    <strong>UTILIDAD PRONOSTICADA: </strong>
                </td>
                <td>
                    <strong>{{ $cotizacion->utilidad_pronosticada_s1 }}</strong>
                </td>
                <td>
                    <strong>{{ $cotizacion->utilidad_pronosticada_s2 }}</strong>
                </td>
                <td colspan="2">
                    <strong>{{ $cotizacion->utilidad_pronosticada_s3 }}</strong>
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
