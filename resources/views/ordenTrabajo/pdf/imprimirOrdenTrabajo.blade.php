<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recibo</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            background-color: #fff;
        }

        /*
        .recibo {
            width: 95%;
            min-height: 5.3in;
            border: 1px solid #000;
            padding: 15px 20px;
            box-sizing: border-box;
        }
        */

        .recibo {
            width: 100%;
            height: 336pt;
            /* border: 1px solid #000; */
            overflow: hidden;
            position: relative;
            padding: 10px;
        }

        .recibo-contenido {
            transform: scale(0.8);
            transform-origin: top left;
            width: 122%;
        }

        .titulo { text-align: center; font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .sub-titulo { text-align: center; font-size: 14px; font-weight: bold; margin-bottom: 10px; }
        #tabla { width: 100%; margin-bottom: 5px; }
        #tabla td { padding: 2px 5px; }
        .text-left { text-align: left; }
        .table { width: 100%; border-collapse: collapse; margin: 5px 0; font-size: 11px; }
        .table th, .table td { border: 1px solid #444; padding: 3px; text-align: center; }
        .table th { background-color: #f0f0f0; font-weight: bold; }
        .flex-section { display: table; width: 100%; margin-top: 5px; }
        .flex-section .col { display: table-cell; width: 50%; vertical-align: top; padding-right: 5px; }
        .hr-small { margin: 5px 0; border: 0; border-top: 1px solid #444; }
    </style>
</head>
<body>
<div class="recibo">
    <div class="recibo-contenido">
        <div class="titulo">NOTA DE RECEPCION N° {{ $factura->numero_factura }}</div>
        <div class="sub-titulo">Nro OT° {{ $nro_orden }}</div>

        <table id="tabla">
            <tr>
                <td><strong>Cliente:</strong></td>
                <td class="text-left">{{ $factura->cliente->nombres." ".$factura->cliente->ap_materno." ".$factura->cliente->ap_paterno }}</td>
                <td><strong>Fecha:</strong></td>
                <td class="text-left">{{ $fecha }}</td>
            </tr>
        </table>

        <hr class="hr-small">
        <table class="table">
            <thead>
                <tr><th>DETALLES DE SERVICIOS</th></tr>
            </thead>
            <tbody>
            @foreach ($ordenesTrabajos as $ordenTrabajo)
                <tr>
                    <td>
                        {{ $ordenTrabajo->prenda?->nombre }} ;
                        [Cantidad:{{ $ordenTrabajo->cantidad }}] ;
                        [Peso:{{ $ordenTrabajo->peso }}] ;
                        [Ojales:{{ $ordenTrabajo->numero_ojales }}/{{ $ordenTrabajo->cantidad }}] ;
                        [Pre-Lavado:{{ $ordenTrabajo->prelavado?->nombre }}] ;
                        [Nevado:{{ $ordenTrabajo->nevado?->nombre }}] ;
                        [Focalizado:{{ $ordenTrabajo->focalizado?->nombre }}] ;
                        [Tipo Tela:{{ $ordenTrabajo->tipoTela?->nombre }}] ;
                        [Color Tela:{{ $ordenTrabajo->colorTela?->nombre }}] ;
                        [Caracteristica Tela:{{ $ordenTrabajo->caracteristicaTela?->nombre }}]
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <hr class="hr-small">
        <table class="table">
            <thead><tr><th>OBSERVACIONES</th></tr></thead>
            <tbody>
            @foreach ($ordenesTrabajos as $ordenTrabajo)
                <tr><td>{{ $ordenTrabajo->observacion }}</td></tr>
            @endforeach
            </tbody>
        </table>

        <div class="flex-section">
            <div class="col">
                <table class="table">
                    <thead><tr><th>FOCALIZADO</th></tr></thead>
                    <tbody>
                    <tr>
                        <td>
                            <strong>Fecha:</strong> __ / __ / ____ <br>
                            <strong>Encargado(a):</strong> <br>
                            ...................................................................................................... <br>
                            ...................................................................................................... <br>
                            <strong>Cant. prendas Foc.:</strong> <br>
                            ...................................................................................................... <br>
                            ......................................................................................................
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col">
                <table class="table">
                    <thead><tr><th>PLANCHADO</th></tr></thead>
                    <tbody>
                    <tr>
                        <td>
                            <strong>Fecha:</strong> __ / __ / ____ <br>
                            <strong>Encargado(a):</strong> <br>
                            ...................................................................................................... <br>
                            ...................................................................................................... <br>
                            <strong>Cant. prendas Planch.:</strong> <br>
                            ...................................................................................................... <br>
                            ......................................................................................................
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
