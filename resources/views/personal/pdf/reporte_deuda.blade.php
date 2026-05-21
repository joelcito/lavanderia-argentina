<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>REPORTE DE DEUDAS</title>

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
        REPORTE DE DEUDA
        <br>
        {{ $deuda->usuario->nombres }} {{ $deuda->usuario->ap_paterno }} {{ $deuda->usuario->ap_materno }}


    </div>

    <table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Total</th>
                <th>Pagado</th>
                <th>Saldo</th>
                <th>Estado</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>{{ $deuda->concepto }}</td>
                <td>{{ number_format($deuda->monto_total, 2) }}</td>
                <td>{{ number_format($deuda->monto_pagado, 2) }}</td>
                <td>{{ number_format($deuda->saldo_pendiente, 2) }}</td>
                <td>{{ $deuda->estado }}</td>
            </tr>
        </tbody>
    </table>

    <br>

    <h4>Movimientos</h4>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Monto</th>
                <th>Descripción</th>
            </tr>
        </thead>

        <tbody>
            @foreach($deuda->detalles as $m)
                <tr>
                    <td>{{ $m->fecha }}</td>
                    <td>{{ $m->tipo_movimiento }}</td>
                    <td>{{ number_format($m->monto, 2) }}</td>
                    <td>{{ $m->descripcion }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>