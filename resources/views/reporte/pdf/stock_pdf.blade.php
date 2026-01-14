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
        TOTAL INVENTARIOS POR SUCURSAL<br>
        Desde {{ $fechaInicio->format('d/m/Y') }} - Hasta {{ $fechaFin->format('d/m/Y') }}

        <br>Sucursal: {{ DB::table('sucursales')->where('id', request()->sucursal_id)->value('nombre') }}
    </div>

    @foreach($reporte as $item)
        <div class="producto">
            Producto: {{ $item['producto'] }}
        </div>

        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cant. Inicio</th>
                    <th>Cant. Ingreso</th>
                    <th>Cant. Salida</th>
                    <th>Cant. Saldo</th>
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
    @endforeach

</body>

</html>