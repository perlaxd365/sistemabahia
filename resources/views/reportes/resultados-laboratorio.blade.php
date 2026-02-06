<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Resultados de Laboratorio</title>

    <style>
        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 100%;
            max-height: 180px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .titulo {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .subtitulo {
            font-size: 12px;
            color: #555;
        }

        hr {
            border: none;
            border-top: 2px solid #0b3c5d;
            margin: 15px 0;
        }

        body {
            font-family: DejaVu Sans;
            font-size: 12px;
            color: #000;
        }

        .titulo {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .orden {
            margin-bottom: 25px;
        }

        /* ===== INFO / DATOS ===== */
        .info {
            margin-bottom: 14px;
        }

        .info div {
            margin-bottom: 4px;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 190px;
        }

        .value {
            color: #000;
        }

        /* ===== SECCIONES ===== */
        .section {
            margin-bottom: 14px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            border-bottom: 1px solid #cfd8dc;
            padding-bottom: 3px;
            margin-bottom: 6px;
        }

        /* ===== TABLA SIGNOS ===== */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table th,
        table td {
            border: 1px solid #cfd8dc;
            padding: 6px;
            font-size: 11px;
        }

        table th {
            background-color: #f4f7f9;
            font-weight: bold;
        }

        /* ===== FIRMA ===== */
        .firma {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
        }

        .firma-linea {
            width: 260px;
            margin: 0 auto 5px auto;
            border-top: 1px solid #0b3c5d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #f2f2f2;
            text-align: left;
        }
    </style>

</head>

<body>

    <!-- ===== PORTADA ===== -->
    <div class="header">
        <img src="{{ $base64 }}" alt="Clínica Bahía">
        <div class="titulo">RESULTADO DE LABORATORIO</div>
    </div>

    <hr>

    <!-- ===== DATOS DE PACIENTE ===== -->
    <div class="section">
        <div class="section-title">Datos del Paciente</div>
        <div class="info">
            <div>
                <span class="label">Nombres completos:</span>
                <span class="value">{{ $paciente->name }}</span>
            </div>
            <div>
                <span class="label">Fecha de nacimiento:</span>
                <span class="value">{{ DateUtil::getFechaSimple($paciente->fecha_nacimiento) }}</span>
            </div>
            <div>
                <span class="label">Número de Historia:</span>
                <span class="value">{{ $orden->atencion->historia->nro_historia }}</span>
            </div>
            <div>
                <span class="label">Orden:</span>
                <span class="value"> {{ $orden->id_orden }}</span>
            </div>
            <div>
                <span class="label">Fecha:</span>
                <span class="value"> {{ DateUtil::getFechaSimple($orden->fecha) }}</span>
            </div>
        </div>
    </div>
    <div class="section">
        <div class="section-title">Resultados</div>
        <div class="info">
            <div class="orden">
                @php
                    $agrupados = [];
                @endphp

                @foreach ($orden->detalles as $det)
                    @php
                        $resultado = trim(strip_tags($det->resultados->resultado ?? ''));
                        $area = $det->examenes->areas->nombre ?? null;
                    @endphp

                    @if ($resultado !== '' && $area)
                        @php
                            $agrupados[$area][] = $det->resultados->resultado;
                        @endphp
                    @endif
                @endforeach
                @foreach ($agrupados as $area => $resultados)
                    <div style="margin-bottom: 10px;">
                        <strong>{{ $area }}</strong><br>
                        <span class="resultado-ckeditor">
                            {!! implode('- - - - - - - - - - - - - - - - - - - - - - - - - - - ', $resultados) !!}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="firma">
            @if ($firma_img)
                <img src="{{ $firma_img }}" style="height:70px; width:auto; max-width:200px;">
                <br> ___________________________<br>
                Laboratorio Clínico
                <br>
                Colegiatura: {{ $profesional->colegiatura_cargo }}
            @endif
        </div>
    </div>

</body>

</html>
