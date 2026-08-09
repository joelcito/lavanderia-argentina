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

    <table>
        <tr>
            <td><strong>Cliente</strong></td>
            <td colspan="3">
                {{ $cotizacion->cliente->nombres }}
                {{ $cotizacion->cliente->ap_paterno }}
                {{ $cotizacion->cliente->ap_materno }}
            </td>
        </tr>

        <tr>
            <td><strong>Fecha</strong></td>
            <td>{{ $cotizacion->created_at }}</td>

            <td><strong>Cantidad Prendas</strong></td>
            <td>{{ $cotizacion->cantidad_prenda }}</td>
        </tr>

        <tr>
            <td><strong>Peso Kg</strong></td>
            <td>{{ $cotizacion->peso_kg }}</td>

            <td><strong>Peso Gr</strong></td>
            <td>{{ $cotizacion->peso_g }}</td>
        </tr>

        <tr>
            <td><strong>Prelavado</strong></td>
            <td>
                {{ $cotizacion->prelavado?->nombre }}
            </td>

            <td><strong>Nevado</strong></td>
            <td>
                {{ $cotizacion->nevado?->nombre }}
            </td>
        </tr>

        <tr>
            <td><strong>Focalizado</strong></td>
            <td>
                {{ $cotizacion->focalizado?->nombre }}
            </td>

            <td><strong>Tipo Tela</strong></td>
            <td>
                {{ $cotizacion->tipo_tela }}
            </td>
        </tr>

        <tr>
            <td><strong>Color Tela</strong></td>
            <td>
                {{ $cotizacion->color_tela }}
            </td>

            <td><strong>Tipo Prenda</strong></td>
            <td>
                {{ $cotizacion->tipo_prenda }}
            </td>
        </tr>

        <tr>
            <td><strong>Descripción</strong></td>
            <td colspan="3">
                {{ $cotizacion->descripcion }}
            </td>
        </tr>
    </table>

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

            {{-- <tr>
                <td colspan="3">
                    <strong>Focalizado</strong>
                </td>
                <td>
                    {{ $cotizacion->precio_focalizado }}
                </td>
                <td>
                    {{ $cotizacion->total_focalizado }}
                </td>
            </tr>

            <tr>
                <td colspan="3">
                    <strong>Planchado</strong>
                </td>
                <td>
                    {{ $cotizacion->precio_planchado }}
                </td>
                <td>
                    {{ $cotizacion->total_planchado }}
                </td>
            </tr> --}}
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
                    <strong>% Ganancia</strong>
                </td>
                <td>
                    {{ $cotizacion->porcentaje_ganacia }}
                </td>

                <td>
                    <strong>Precio Venta Pronosticado</strong>
                </td>
                <td>
                    {{ $cotizacion->precio_venta_pronosticado }}
                </td>

                <td>
                    <strong>
                        Precio Venta Pronosticado S3:
                        {{ $cotizacion->precio_venta_pronosticado_s3 }}
                    </strong>
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
