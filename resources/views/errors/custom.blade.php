<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - {{ $code ?? 'Error' }}</title>

    <style>
        #outer {
            width: 100%;
            text-align: center;
            padding-top: 50px;
        }

        #inner {
            display: inline-block;
            width: 50%;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18pt;
            background-color: #FDFBFE;
            margin: 0;
        }

        .button {
            background-color: #008CBA;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 6px;
        }

        img {
            max-width: 100%;
            width: 420px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div id="outer">
        <div id="inner">

            <img src="{{ asset('assets/img/401.png') }}" alt="Error Image">
            <br>

            {{-- Imagen fija, puedes cambiarla --}}
            <img src="{{ asset('assets/img/ef8bbd4554dedcc2fd1fd15ab0ebd7a1.gif') }}" alt="Error Image">

            {{-- Mensaje dinámico que viene del controlador --}}
            <p style="color: rgb(228, 82, 82);">{{ $message ?? 'Ha ocurrido un error inesperado.' }}</p>

            <br><br>

            <a href="{{ url()->previous() }}" class="button">REGRESAR</a>

        </div>
    </div>
</body>

</html>
