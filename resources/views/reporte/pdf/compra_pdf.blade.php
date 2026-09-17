<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>STOCK HISTÓRICO</title>

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
        REPORTE STOCK HISTÓRICO POR COMPRA
    </div>

    <div class="subtitulo">
        Código de Compra: <strong>{{ $codigoCompra }}</strong><br>
        Sucursal: <strong>{{ $sucursal }}</strong><br>
        Desde {{ $fechaInicio->format('d/m/Y') }}
        hasta {{ $fechaFin->format('d/m/Y') }}
    </div>

    @forelse($reporte as $item)

        <div class="producto">
            Producto: {{ $item['producto'] }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cantidad Inicio</th>
                    <th>Cantidad Ingreso</th>
                    <th>Cantidad Salida</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item['detalle'] as $row)
                    <tr>
                        <td>{{ $row['fecha'] }}</td>
                        <td>{{ number_format($row['inicio'], 4) }}</td>
                        <td>{{ number_format($row['ingreso'], 4) }}</td>
                        <td>{{ number_format($row['salida'], 4) }}</td>
                        <td>{{ number_format($row['saldo'], 4) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    @empty
        <p style="text-align:center;">
            No existen registros para los filtros seleccionados.
        </p>
    @endforelse

</body>

</html>