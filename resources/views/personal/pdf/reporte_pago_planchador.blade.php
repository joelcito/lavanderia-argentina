<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>REPORTE DE PAGOS</title>

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
            padding: 4px;
            text-align: center;
        }

        th {
            background-color: #f0f0f0;
        }

        .resaltado {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="titulo">
        REPORTE DE PAGOS DE PLANCHADORES <br>
        Desde {{ $inicioFormato }} - Hasta {{ $finFormato }} <br>
        Sucursal: {{ $sucursalNombre }}
    </div>

    @foreach($reporte as $r)

        <table>

            <tr>
                <th colspan="7">{{ $r['nombre'] }}</th>
            </tr>

            <tr>
                <th>FACTURA</th>
                <th>OT</th>
                <th>PRENDA</th>
                <th>CATEGORÍA</th>
                <th>CANTIDAD</th>
                <th>PRECIO</th>
                <th>TOTAL</th>
            </tr>

            @foreach($r['detalle'] as $d)
                <tr>
                    <td>{{ $d['factura'] }}</td>
                    <td>{{ $d['ot'] }}</td>
                    <td>{{ $d['prenda'] }}</td>
                    <td style="white-space: pre-line;">{{ $d['categoria'] }}</td>
                    <td>{{ $d['cantidad'] }}</td>
                    <td>{{ number_format($d['precio'], 2) }}</td>
                    <td>{{ number_format($d['total'], 2) }}</td>
                </tr>
            @endforeach

            <tr class="resaltado">
                <td colspan="6">TOTAL PRODUCCIÓN</td>
                <td>{{ number_format($r['total_produccion'], 2) }}</td>
            </tr>

            <tr>
                <td colspan="6">ADELANTOS</td>
                <td>{{ number_format($r['adelantos'], 2) }}</td>
            </tr>

            <tr>
                <td colspan="6">DESCUENTOS</td>
                <td>{{ number_format($r['descuentos'], 2) }}</td>
            </tr>

            <tr>
                <td colspan="6">TOTAL PAGADO</td>
                <td>{{ number_format($r['pagado'], 2) }}</td>
            </tr>

            <tr>
                <td colspan="6"><strong>TOTAL FINAL</strong></td>
                <td><strong>{{ number_format($r['total_final'], 2) }}</strong></td>
            </tr>

        </table>

        <br>

    @endforeach

</body>

</html>