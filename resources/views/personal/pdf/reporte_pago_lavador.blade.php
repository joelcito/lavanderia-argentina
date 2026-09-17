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
        REPORTE DE PAGOS DE LAVADORES <br>
        Desde {{ \Carbon\Carbon::parse($inicio)->format('d/m/Y') }} - Hasta
        {{ \Carbon\Carbon::parse($fin)->format('d/m/Y') }}
        <br>
        Sucursal:
        {{ DB::table('sucursales')->where('id', request()->sucursal_id)->value('nombre') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Personal</th>
                <th>Monto de la Semana</th>
                <th>Descuento/Adelanto</th>
                <th>Total a Pagar Bs.</th>
                <th>Estado</th>
            </tr>
        </thead>

        <tbody>
            @foreach($reporte as $r)
                <tr>
                    <td>{{ $r['nombres']  }} {{ $r['ap_paterno']  }} {{ $r['ap_materno']  }}</td>
                    <td>{{ number_format($r['monto_semana'], 2) }}</td>
                    <td>{{ number_format($r['descuento'], 2) }}</td>
                    <td class="resaltado">{{ number_format($r['total'], 2) }}</td>
                    <td>{{ $r['total'] > 0 ? 'PAGADO' : 'NO PAGADO' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>