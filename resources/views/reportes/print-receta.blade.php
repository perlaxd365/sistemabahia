<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .left {
            text-align: left;
        }

        hr {
            border: none;
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            width: 70mm;
            margin-left: 7%;
            padding-top: 7%;
        }

        img {
            max-width: 140px;
        }
    </style>
</head>

<body>

    <div class="center">
        <img src="{{ public_path('images/logo-clinica.png') }}" alt="Logo">
        <br>
        <strong>RECETA MÉDICA</strong>
    </div>

    <hr>

    Paciente:<br>
    <strong>{{ $paciente->name }}</strong><br>
    DNI: {{ $paciente->dni }}<br>
    Fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }}

    <hr>

    <strong>TRATAMIENTO:</strong><br><br>

    {!! nl2br(e($consulta->tratamiento_consulta)) !!}

    <br><br>

    <hr>

    @if ($firma_img)
        <div class="center">
            <img src="{{ $firma_img }}" width="100">
        </div>
    @endif

    <div class="center">
        <br>
        Dr(a). {{ $profesional->name ?? '' }} <br>
        CMP: {{ $profesional->colegiatura_cargo ?? '' }}
    </div>

    <hr>

    <div class="center">
        Clínica Bahía<br>
        Trabajando por tu salud
    </div>

</body>

</html>
