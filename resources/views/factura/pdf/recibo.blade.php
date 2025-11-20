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
            background-color: #fde9d9; /* opcional */
        }

        .recibo {
            width: 100%;
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
    </style>
</head>
<body>
    <div class="recibo">
        <div class="titulo">RECIBO DE PAGO N° {{ $factura->numero_factura }}</div>
        <div class="contenido">
            <p><strong>Cliente:</strong> {{ $factura->cliente->nombres." ".$factura->cliente->ap_materno." ".$factura->cliente->ap_paterno }}</p>
            <p><strong>Fecha:</strong> {{ $fecha }}</p>
            <p><strong>Monto:</strong> Bs {{ number_format($factura->total, 2, ',', '.') }}</p>
            {{-- <p><strong>Detalle:</strong> {{ $detalle }}</p> --}}
            @php
                $ordenesTrabajo = $factura->ordenTrabajos;
            @endphp
            <table>
                <thead>
                    <tr>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
</body>
</html>
