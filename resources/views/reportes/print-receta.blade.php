<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            color: #2c3e50;
        }

        /* HEADER */

        .header {
            width: 100%;
            height: 140px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-left {
            background: #2da7a7;
            color: white;
            padding: 25px;
            width: 45%;
        }

        .header-center {
            background: #7fd3d0;
            width: 30%;
            text-align: center;
            padding-top: 15px;
        }

        .header-right {
            background: #7fd3d0;
            width: 25%;
            text-align: center;
        }

        /* LOGO */

        .logo {
            width: 140px;
            margin-bottom: 5px;
        }

        /* TITULO */

        .titulo {
            color: white;
            font-size: 18px;
            letter-spacing: 3px;
            font-weight: bold;
        }

        /* DOCTOR */

        .doctor {
            font-size: 28px;
            font-weight: bold;
        }

        .qual {
            font-size: 12px;
            letter-spacing: 3px;
            margin-top: 5px;
        }

        /* ICONO */

        .icon {
            width: 70px;
            margin-top: 30px;
        }

        /* LOGO CENTRO */

        .center {
            position: absolute;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
            color: white;
        }

        .center img {
            width: 90px;
            margin-bottom: 5px;
        }

        .center strong {
            font-size: 16px;
            letter-spacing: 2px;
        }

        /* DOCTOR */

        .doctor {
            font-size: 26px;
            font-weight: bold;
        }

        .qual {
            font-size: 12px;
            letter-spacing: 3px;
            margin-top: 4px;
        }

        .icon {
            width: 60px;
            margin-top: 20px;
        }

        /* CONTENIDO */

        .content {
            padding: 50px 60px;
        }

        .row {
            margin-bottom: 20px;
            font-size: 14px;
        }

        .line {
            border-bottom: 1px solid #8aa1a8;
            display: inline-block;
            min-width: 250px;
            margin-left: 10px;
        }

        .line-small {
            border-bottom: 1px solid #8aa1a8;
            display: inline-block;
            min-width: 120px;
            margin-left: 10px;
        }

        .diagnostico {
            border-bottom: 1px solid #8aa1a8;
            display: inline-block;
            width: 520px;
            margin-left: 10px;
        }

        /* RX */

        .rx {
            font-size: 60px;
            color: #1f3b57;
            margin-top: 30px;
            font-weight: bold;
        }

        /* TRATAMIENTO */

        .tratamiento {
            margin-top: 20px;
            min-height: 340px;
            font-size: 16px;
            line-height: 1.8;
        }

        /* FIRMA */

        .firma {
            margin-top: 60px;
            text-align: right;
        }

        .firma img {
            width: 170px;
        }

        .firma-line {
            border-top: 1px solid #666;
            width: 230px;
            margin-left: auto;
            text-align: center;
            font-size: 12px;
            margin-top: 5px;
        }

        /* FOOTER */

        .footer {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 12px;
            color: #3d566e;
        }
    </style>

</head>

<body>

    <!-- HEADER -->

    <!-- HEADER -->

    <div class="header">

        <table class="header-table">
            <tr>

                <!-- IZQUIERDA -->
                <td class="header-left">

                    <div class="doctor">
                        Dr. {{ $profesional->name ?? '' }}
                    </div>

                    <div class="qual">
                        CMP {{ $profesional->colegiatura_cargo ?? '' }}
                    </div>

                </td>

                <!-- CENTRO -->
                <td class="header-center">

                    <img src="{{ public_path('images/logo-completo.png') }}" style="height:120px;">

                    <div class="titulo">
                        RECETA MÉDICA
                    </div>

                </td>

                <!-- DERECHA -->
                <td class="header-right">

                    <img src="{{ public_path('images/estetoscopio.png') }}" class="icon">

                </td>

            </tr>
        </table>

    </div>

    <!-- CONTENIDO -->

    <div class="content">

        <div class="row">
            Paciente:
            <span class="line">{{ $paciente->name }}</span>

            <span style="margin-left:40px">Fecha:</span>
            <span class="line-small">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
        </div>

        <div class="row">
            DNI:
            <span class="line-small">{{ $paciente->dni }}</span>
        </div>

        <div class="tratamiento">
            {!! nl2br(e($consulta->tratamiento_consulta)) !!}
        </div>

        @if ($firma_img)
            <div class="firma">
                <img src="{{ $firma_img }}">
                <div class="firma-line">
                    Firma del Médico
                </div>
            </div>
        @endif

    </div>

    <!-- FOOTER -->

    <div class="footer">

        <strong>Clínica Bahía</strong>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Nuevo Chimbote - Perú
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Tel: +51 905 431 945

    </div>

</body>

</html>
