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
        REPORTE ESTRUCTURA DE COSTOS
    </div>

    <div class="subtitulo">
        Carga: <strong>{{ $solicitud }}</strong><br>
        Factura: <strong>{{ $factura }}</strong><br>
        OTs: <strong>{{ $ots }}</strong><br>

        Fecha del reporte:
        <strong>{{ $fechaInicio->format('d/m/Y') }} </strong>
    </div>

    @forelse($reporte as $item)


        <h4>{{ $item['proceso'] }}</h4>

        <table>
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Producto</th>
                    <th>Cantidad (Gr)</th>
                    <th>Precio Químico</th>
                    <th>Costo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item['detalle'] as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $row['producto'] }}</td>
                        <td>{{ number_format($row['cantidad'], 2) }}</td>
                        <td>{{ number_format($row['precio'], 2) }}</td>
                        <td>{{ number_format($row['costo'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>





    @empty
        <p style="text-align:center;">
            No existen registros para los filtros seleccionados.
        </p>
    @endforelse
    <table>
        <tr>
            <td><strong>Costo Unitario</strong></td>
            <td>{{ number_format($costoUnitario, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Sueldos y Salarios</strong></td>
            <td>{{ number_format($sueldos, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Costo Total</strong></td>
            <td>{{ number_format($costoTotal, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Precio</strong></td>
            <td>{{ number_format($precio, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Utilidad</strong></td>
            <td>{{ number_format($utilidad, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Margen de Contribución</strong></td>
            <td>{{ $margen }}</td>
        </tr>
    </table>
</body>

</html>