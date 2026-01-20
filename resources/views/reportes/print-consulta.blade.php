<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Historia Clínica</title>

    <style>
        @page {
            margin: 30px 35px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #0b3c5d;
        }

        /* ===== CABECERA / PORTADA ===== */
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
    </style>
</head>

<body>

    <!-- ===== PORTADA ===== -->
    <div class="header">
        <img src="{{ $base64 }}" alt="Clínica Bahía">
        <div class="titulo">HISTORIA CLÍNICA</div>
        <div class="subtitulo">Consulta Médica</div>
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
                <span class="value">{{ $paciente->fecha_nacimiento }}</span>
            </div>
            <div>
                <span class="label">Teléfono:</span>
                <span class="value">{{ $paciente->telefono }}</span>
            </div>
            <div>
                <span class="label">Dirección:</span>
                <span class="value">{{ $paciente->direccion }}</span>
            </div>
        </div>
    </div>

    <!-- ===== DATOS DE ATENCIÓN ===== -->
    <div class="section">
        <div class="section-title">Datos de Atención</div>
        <div class="info">
            <div>
                <span class="label">N° Historia Clínica:</span>
                <span class="value">{{ $historia->nro_historia }}</span>
            </div>
            <div>
                <span class="label">Fecha de Consulta:</span>
                <span class="value">{{ $consulta->fecha_consulta }}</span>
            </div>
        </div>
    </div>

    <!-- ===== MOTIVO DE CONSULTA ===== -->
    <div class="section">
        <div class="section-title">Motivo de Consulta</div>
        <div><span class="label">Molestia principal:</span><span
                class="value">{{ $consulta->molestia_consulta }}</span></div>
        <div><span class="label">Tiempo de evolución:</span><span
                class="value">{{ $consulta->tiempo_consulta }}</span></div>
        <div><span class="label">Inicio:</span><span class="value">{{ $consulta->inicio_consulta }}</span></div>
        <div><span class="label">Curso:</span><span class="value">{{ $consulta->curso_consulta }}</span></div>
    </div>

    <!-- ===== ENFERMEDAD ACTUAL ===== -->
    <div class="section">
        <div class="section-title">Enfermedad Actual</div>
        <div class="value">{{ $consulta->enfermedad_consulta }}</div>
    </div>

    <!-- ===== ANTECEDENTES ===== -->
    <div class="section">
        <div class="section-title">Antecedentes</div>
        <div><span class="label">Familiares:</span><span
                class="value">{{ $consulta->atecedente_familiar_consulta }}</span></div>
        <div><span class="label">Patológicos:</span><span
                class="value">{{ $consulta->atecedente_patologico_consulta }}</span></div>
    </div>

    <!-- ===== SIGNOS VITALES ===== -->
    <div class="section">
        <div class="section-title">Signos Vitales</div>
        <table>
            <tr>
                <th>Peso</th>
                <th>Talla</th>
                <th>IMC</th>
                <th>Temp.</th>
                <th>PA</th>
                <th>FC</th>
                <th>Sat O₂</th>
            </tr>
            <tr>
                <td>{{ $consulta->peso_consulta }}</td>
                <td>{{ $consulta->talla_consulta }}</td>
                <td>{{ $consulta->imc_consulta }}</td>
                <td>{{ $consulta->temperatura_consulta }}</td>
                <td>{{ $consulta->presion_consulta }}</td>
                <td>{{ $consulta->frecuencia_consulta }}</td>
                <td>{{ $consulta->saturacion_consulta }}</td>
            </tr>
        </table>
    </div>

    <!-- ===== EXAMEN FÍSICO ===== -->
    <div class="section">
        <div class="section-title">Examen Físico</div>
        <div class="value">{{ $consulta->examen_consulta }}</div>
    </div>

    <!-- ===== IMPRESIÓN DIAGNÓSTICA ===== -->
    <div class="section">
        <div class="section-title">Impresión Diagnóstica</div>
        <div class="value">{{ $consulta->impresion_consulta }}</div>
    </div>

    <!-- ===== EXÁMENES AUXILIARES ===== -->
    <div class="section">
        <div class="section-title">Exámenes Auxiliares</div>
        <div class="value">{{ $consulta->examen_auxiliar_consulta }}</div>
    </div>

    <!-- ===== TRATAMIENTO ===== -->
    <div class="section">
        <div class="section-title">Tratamiento</div>
        <div class="value">{{ $consulta->tratamiento_consulta }}</div>
    </div>

    <!-- ===== FIRMA ===== -->

    <div class="firma">
        @if ($firma_img)
            <img src="{{ $firma_img }}" style="height:70px; width:auto; max-width:200px;">
            <br> ___________________________<br>
            Laboratorio Clínico <br>
            Colegiatura: {{ $profesional->colegiatura_cargo }}
        @endif
    </div>

</body>

</html>
