<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <title>
        Receta {{ $receta->nombre }}
    </title>

    <style>
        @page {
            margin: 20px 25px;
        }


        body {

            font-family:
                DejaVu Sans,
                sans-serif;

            font-size:
                10px;

            color:
                #222;

        }


        .titulo {

            text-align:
                center;

            font-size:
                18px;

            font-weight:
                bold;

            margin-bottom:
                3px;

        }


        .subtitulo {

            text-align:
                center;

            font-size:
                11px;

            margin-bottom:
                15px;

            color:
                #555;

        }


        .tabla {

            width:
                100%;

            border-collapse:
                collapse;

            margin-bottom:
                15px;

        }


        .tabla th {

            border:
                1px solid #333;

            padding:
                5px;

            background:
                #e9ecef;

            font-size:
                9px;

        }


        .tabla td {

            border:
                1px solid #333;

            padding:
                5px;

            vertical-align:
                middle;

        }


        .tabla-info td {

            padding:
                5px;

        }


        .label {

            font-weight:
                bold;

            background:
                #f1f1f1;

            width:
                18%;

        }


        .proceso {

            background:
                #333;

            color:
                white;

            font-weight:
                bold;

            font-size:
                11px;

            padding:
                7px;

            margin-top:
                15px;

            margin-bottom:
                0;

        }


        .descripcion {

            border:
                1px solid #aaa;

            padding:
                7px;

            margin-bottom:
                15px;

        }


        .text-center {

            text-align:
                center;

        }


        .text-right {

            text-align:
                right;

        }


        .numero {

            white-space:
                nowrap;

        }


        .firma {

            width:
                100%;

            margin-top:
                50px;

        }


        .firma td {

            width:
                50%;

            text-align:
                center;

            vertical-align:
                bottom;

            padding:
                0 30px;

        }


        .linea-firma {

            border-top:
                1px solid #000;

            padding-top:
                5px;

        }


        .pie {

            margin-top:
                20px;

            text-align:
                right;

            font-size:
                8px;

            color:
                #777;

        }
    </style>

</head>


<body>


    {{-- ================================================= --}}
    {{-- TITULO --}}
    {{-- ================================================= --}}

    <div class="titulo">

        RECETA DE PRODUCCIÓN

    </div>


    <div class="subtitulo">

        {{ strtoupper($receta->nombre) }}

    </div>



    {{-- ================================================= --}}
    {{-- INFORMACIÓN GENERAL --}}
    {{-- ================================================= --}}

    <table class="tabla tabla-info">

        <tr>

            <td class="label">

                N° Receta

            </td>

            <td>

                {{ $receta->id }}

            </td>


            <td class="label">

                Nombre

            </td>

            <td>

                {{ $receta->nombre }}

            </td>

        </tr>


        <tr>

            <td class="label">

                Tipo Tela

            </td>

            <td>

                {{ $receta->tipoTela?->nombre ?? '-' }}

            </td>


            <td class="label">

                Color

            </td>

            <td>

                {{ $receta->colorTela?->nombre ?? '-' }}

            </td>

        </tr>


        <tr>

            <td class="label">

                Nombre Tela

            </td>

            <td>

                {{ $receta->nombreTela?->nombre ?? '-' }}

            </td>


            <td class="label">

                Proceso Principal

            </td>

            <td>

                {{ $receta->tipoProceso?->nombre ?? '-' }}

            </td>

        </tr>


        <tr>

            <td class="label">

                Prelavado

            </td>

            <td>

                {{ $receta->prelavado?->nombre ?? '-' }}

            </td>


            <td class="label">

                Focalizado

            </td>

            <td>

                {{ $receta->focalizado?->nombre ?? '-' }}

            </td>

        </tr>


        <tr>

            <td class="label">

                Nevado

            </td>

            <td>

                {{ $receta->nevado?->nombre ?? '-' }}

            </td>


            <td class="label">

                Característica

            </td>

            <td>

                {{ $receta->caracteristica?->nombre ?? '-' }}

            </td>

        </tr>

    </table>



    {{-- ================================================= --}}
    {{-- DESCRIPCION --}}
    {{-- ================================================= --}}

    @if(!empty($receta->descripcion))

    <div>

        <strong>
            Descripción:
        </strong>

    </div>


    <div class="descripcion">

        {{ $receta->descripcion }}

    </div>

    @endif



    {{-- ================================================= --}}
    {{-- PROCESOS --}}
    {{-- ================================================= --}}

    @foreach($procesos as $proceso)


    <div class="proceso">

        PROCESO
        {{ $proceso['orden_proceso'] }}

        -

        {{ strtoupper(
        $proceso['nombre_proceso']
        ) }}

    </div>


    <table class="tabla">

        <thead>

            <tr>

                <th style="width: 5%">
                    #
                </th>

                <th style="width: 25%">
                    Producto
                </th>

                <th style="width: 9%">
                    %
                </th>

                <th style="width: 10%">
                    Cantidad
                </th>

                <th style="width: 9%">
                    Total
                </th>

                <th style="width: 8%">
                    Tiempo
                </th>

                <th style="width: 8%">
                    Temp.
                </th>

                <th style="width: 7%">
                    PH
                </th>

                <th style="width: 7%">
                    RB
                </th>

                <th style="width: 12%">
                    Observación
                </th>

            </tr>

        </thead>


        <tbody>

            @foreach($proceso['productos'] as $detalle)

            <tr>

                <td class="text-center">

                    {{ $detalle->orden_producto }}

                </td>


                <td>

                    {{ $detalle->producto?->nombre ?? '-' }}

                </td>


                <td class="text-right numero">

                    {{ $detalle->porcentaje !== null
                    ? number_format(
                    $detalle->porcentaje,
                    5,
                    '.',
                    ''
                    )
                    : '-'
                    }}

                </td>


                <td class="text-right numero">

                    {{ $detalle->cantidad !== null
                    ? number_format(
                    $detalle->cantidad,
                    5,
                    '.',
                    ''
                    )
                    : '-'
                    }}

                </td>


                <td class="text-right numero">

                    {{ $detalle->total !== null
                    ? number_format(
                    $detalle->total,
                    5,
                    '.',
                    ''
                    )
                    : '-'
                    }}

                </td>


                <td class="text-center numero">

                    @if(
                    $detalle->tiempo
                    !== null
                    )

                    {{ number_format(
                    $detalle->tiempo,
                    2,
                    '.',
                    ''
                    ) }}

                    min

                    @else

                    -

                    @endif

                </td>


                <td class="text-center numero">

                    @if(
                    $detalle->temperatura
                    !== null
                    )

                    {{ number_format(
                    $detalle->temperatura,
                    2,
                    '.',
                    ''
                    ) }}

                    °C

                    @else

                    -

                    @endif

                </td>


                <td class="text-center numero">

                    {{ $detalle->ph ?? '-' }}

                </td>


                <td class="text-center numero">

                    {{ $detalle->rb ?? '-' }}

                </td>


                <td>

                    {{ $detalle->descripcion ?? '-' }}

                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

    @endforeach



    {{-- ================================================= --}}
    {{-- SIN DETALLES --}}
    {{-- ================================================= --}}

    @if($procesos->count() === 0)

    <table class="tabla">

        <tr>

            <td class="text-center">

                Esta receta no tiene procesos
                o productos registrados.

            </td>

        </tr>

    </table>

    @endif



    {{-- ================================================= --}}
    {{-- FIRMAS --}}
    {{-- ================================================= --}}

    <table class="firma">

        <tr>

            <td>

                <div class="linea-firma">

                    RESPONSABLE DE PRODUCCIÓN

                </div>

            </td>


            <td>

                <div class="linea-firma">

                    LAVADOR / OPERADOR

                </div>

            </td>

        </tr>

    </table>



    <div class="pie">

        Documento generado:
        {{ now()->format('d/m/Y H:i') }}

    </div>


</body>

</html>
