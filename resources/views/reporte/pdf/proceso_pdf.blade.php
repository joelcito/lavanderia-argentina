<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ficha de Proceso OT {{ $ordenTrabajo->nro_ot }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        header {
            text-align: center;
            margin-bottom: 10px;
        }

        header img {
            height: 60px;
            margin-bottom: 5px;
        }

        h1,
        h2,
        h3 {
            margin: 0;
            padding: 0;
        }

        .section {
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            font-size: 11px;
        }

        th {
            background-color: #f0f0f0;
        }

        .totals {
            font-weight: bold;
            text-align: right;
        }

        .observaciones {
            margin-top: 10px;
        }

        .info-central td {
            border: none;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Encabezado con logo e info central -->
    <header>
        <!-- <img src="{{ public_path('logo.png') }}" alt="Logo Empresa"> -->
        <h2>FICHA DE PROCESO</h2>
    </header>

    <!-- Información central: OT, Cliente, Fecha, Totales -->
    <div class="section">
        <table class="info-central">
            <tr>
                <td>OT: {{ $ordenTrabajo->nro_ot }}</td>
                <td>Cliente: {{ $factura->cliente?->name ?? 'N/A' }}</td>
                <td>Fecha: {{ $fechaImpresion }}</td>
            </tr>
            <tr>
                <td>Total Prendas: {{ $ordenTrabajo->cantidad }}</td>
                <td>Total Peso: {{ $ordenTrabajo->peso }} kg</td>
                <td>Factura N°: {{ $factura->numero_factura }}</td>
            </tr>
        </table>
    </div>

    <!-- Detalles de Servicios -->
    <div class="section">
        <h3>DETALLES DE SERVICIOS</h3>
        <table>
            <thead>
                <tr>
                    <th>Prenda</th>
                    <th>Cantidad</th>
                    <th>Peso</th>
                    <th>Pre-Lavado</th>
                    <th>Nevado</th>
                    <th>Focalizado</th>
                    <th>Tipo Tela</th>
                    <th>Color Tela</th>
                    <th>Característica</th>
                    <th>Ojales</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $ordenTrabajo->prenda?->nombre }}</td>
                    <td>{{ $ordenTrabajo->cantidad }}</td>
                    <td>{{ $ordenTrabajo->peso }}</td>
                    <td>{{ $ordenTrabajo->prelavado?->nombre }}</td>
                    <td>{{ $ordenTrabajo->nevado?->nombre }}</td>
                    <td>{{ $ordenTrabajo->focalizado?->nombre }}</td>
                    <td>{{ $ordenTrabajo->tipoTela?->nombre }}</td>
                    <td>{{ $ordenTrabajo->colorTela?->nombre }}</td>
                    <td>{{ $ordenTrabajo->caracteristicaTela?->nombre }}</td>
                    <td>{{ $ordenTrabajo->numero_ojales ?? '-' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Procesos -->
    <div class="section">
        <h3>PROCESOS</h3>
        <table>
            <thead>
                <tr>
                    <th>Ciclo</th>
                    <th>Cant (g)</th>
                    <th>Producto</th>
                    <th>%</th>
                    <th>gr/l</th>
                    <th>Tiempo</th>
                    <th>Temp (°C)</th>
                    <th>pH</th>
                    <th>R:B</th>
                </tr>
            </thead>
            <tbody>
                @foreach($procesos as $proceso)
                    <tr>
                        <td>{{ $proceso->tipoProceso?->nombre }}</td>
                        <td>{{ $proceso->cantida }}</td>
                        <td>{{ $proceso->producto?->nombre }}</td>
                        <td>{{ $proceso->porcentaje }}</td>
                        <td>{{ $proceso->gr_litro }}</td>
                        <td>{{ $proceso->tiempo }}</td>
                        <td>{{ $proceso->temperatura }}</td>
                        <td>{{ $proceso->ph }}</td>
                        <td>{{ $proceso->rb }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section observaciones">
        <p><strong>Observaciones:</strong> {{ $ordenTrabajo->descripcion ?? '-' }}</p>
    </div>


</body>

</html>