<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo</title>
    <style>
        @page {
            margin: 0; /* elimina márgenes del PDF */
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #ffffff; /* opcional */
            font-size: 10px;
        }

        .recibo {
            width: 95%;
            height: 5.3in; /* un poco menos que 5.5 para evitar salto */
            border: 1px solid #000;
            padding: 20px;
            box-sizing: border-box;
            /* background-color:red; */
        }

        .titulo {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .contenido {
            font-size: 14px;
            line-height: 1.5;
            /* background-color:red; */
        }

        p {
            margin: 4px 0;
        }

        #tabla{
            width: 100%;
        }

        .text-left{
            text-align: left;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #444;
            padding: 2px;
            text-align: center;
        }

        .table th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="recibo">
        <div class="titulo">NOTA DE RECEPCION N° {{ $factura->numero_factura }}</div>
        <table id="tabla">
            <tr>
                <td><strong>Cliente:</strong></td>
                <td class="text-left">{{ $factura->cliente->nombres." ".$factura->cliente->ap_materno." ".$factura->cliente->ap_paterno }}</td>
                <td><strong>Fecha:</strong></td>
                <td class="text-left">{{ $fecha }}</td>
            </tr>
            <tr>
                <td><strong>Direccion:</strong></td>
                <td class="text-left">{{ $factura->cliente->direccion }}</td>
                <td><strong>Celular:</strong></td>
                <td class="text-left">{{ $factura->cliente->celular }}</td>
            </tr>
        </table>
        <hr>

        <table class="table">
            <thead>
                <tr>
                    <th>PRENDA</th>
                    <th>TELA</th>
                    <th>PRELAVADO</th>
                    <th>NEVADO</th>
                    <th>FOCALIZADO</th>
                    <th>CANTIDAD</th>
                    <th>NUMERO OJALES</th>
                    <th>PESO</th>
                    <th>PRECIO</th>
                    <th>SUBTOTAL</th>
                    <th>N° OT</th>
                    <th>OBS</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $ots = $factura->ordenTrabajos;
                    $total = 0;
                @endphp
                @foreach ( $ots as $ot)
                    @php
                        $total = $total + $ot->subtotal;
                    @endphp
                    <tr>
                        <td>{{ $ot->prenda?->nombre }}</td>
                        <td>{{ $ot->tela?->nombre }}</td>
                        <td>{{ $ot->prelavado?->nombre }}</td>
                        <td>{{ $ot->nevado?->nombre }}</td>
                        <td>{{ $ot->focalizado?->nombre }}</td>
                        <td>{{ $ot->cantidad }}</td>
                        <td>{{ $ot->numero_ojales }}</td>
                        <td>{{ $ot->peso }}</td>
                        <td>{{ $ot->precio }}</td>
                        <td>{{ $ot->subtotal }}</td>
                        <td>{{ $ot->nro_ot }}</td>
                        <td>{{ $ot->observacion }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9"><strong>TOTAL</strong></td>
                    <td colspan="3">{{ number_format($total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>
</html>
