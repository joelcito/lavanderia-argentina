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
        REPORTE DE PAGOS DE FOCALIZADORES <br>
        <p>Desde: {{ $inicioFormato }} | Hasta: {{ $finFormato }}</p>
        <br>
        Sucursal:
        {{ DB::table('sucursales')->where('id', request()->sucursal_id)->value('nombre') }}
    </div>

    <table>


        <tbody>

            @foreach($reporte as $r)

                <h3>Nombre: {{ $r['nombre'] }} </h3>

                <table border="1" width="100%" cellspacing="0" cellpadding="5">
                    <thead>
                        <tr>
                            <th>Factura</th>
                            <th>OT</th>
                            <th>Cliente</th>
                            <th>Cantidad de prendas</th>
                            <th>Precio</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($r['filas'] as $f)
                            <tr>
                                <td>{{ $f['factura'] }}</td>
                                <td>{{ $f['ot'] }}</td>
                                <td>{{ $f['cliente'] }}</td>
                                <td>{{ $f['cantidad'] }}</td>
                                <td>Bs {{ number_format($f['precio'], 2) }}</td>
                                <td>Bs {{ number_format($f['subtotal'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <br>

                <table width="100%">
                    <tr>
                        <td><b>Total prendas:</b> {{ $r['total_cantidad'] }}</td>
                        <td><b>Total:</b> Bs {{ number_format($r['total'], 2) }}</td>
                    </tr>
                    <tr>
                        <td><b>Adelantos:</b> Bs {{ number_format($r['adelantos'], 2) }}</td>
                        <td><b>Descuentos:</b> Bs {{ number_format($r['descuentos'], 2) }}</td>
                    </tr>
                    <tr>
                        <td><b>Total Final:</b> Bs {{ number_format($r['total_final'], 2) }}</td>
                        <td><b>Estado:</b> {{ $r['estado'] }}</td>
                    </tr>
                </table>

                <hr>

            @endforeach
        </tbody>
    </table>

</body>

</html>