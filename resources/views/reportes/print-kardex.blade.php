<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Kardex de Medicamentos</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2d3d;
        }

        .header {
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-table {
            width: 100%;
        }

        .header-table td {
            vertical-align: middle;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            color: #0d6efd;
        }

        .subtitle {
            font-size: 12px;
            color: #6c757d;
        }

        .filters {
            margin-bottom: 15px;
            background: #f8f9fa;
            padding: 8px;
            border-radius: 4px;
        }

        .filters b {
            color: #0d6efd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #e9f1ff;
        }

        th,
        td {
            border: 1px solid #ced4da;
            padding: 6px;
            text-align: center;
        }

        th {
            font-weight: bold;
            font-size: 11px;
        }

        td.text-left {
            text-align: left;
        }

        .entrada {
            color: #198754;
            font-weight: bold;
        }

        .salida {
            color: #dc3545;
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: right;
            color: #6c757d;
        }
    </style>
</head>

<body>

    <!-- CABECERA -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td width="20%">
                    <img src="{{ $logoBase64 }}" width="120">
                </td>
                <td width="80%">
                    <div class="title">KARDEX DE MEDICAMENTOS</div>
                    <div class="subtitle">
                        Control de entradas y salidas de farmacia
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- FILTROS -->
    <div class="filters">
        <b>Medicamento:</b> {{ $medicamentoSeleccionado ?? 'Todos' }} <br>
        <b>Fecha:</b> {{ $fechaInicio ?? '—' }} al {{ $fechaFin ?? '—' }} <br>
        <b>Tipo:</b> {{ $tipoMovimiento ?? 'Todos' }}
    </div>

    <!-- TABLA KARDEX -->
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Movimiento</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Stock Anterior</th>
                <th>Cantidad</th>
                <th>Stock Nuevo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kardex as $mov)
                <tr>
                    <td>{{ $mov->created_at->format('d/m/Y H:i') }}</td>

                    <td class="{{ $mov->tipo_movimiento === 'ENTRADA' ? 'entrada' : 'salida' }}">
                        {{ $mov->tipo_movimiento }}
                    </td>

                    <td class="text-left">
                        {{ $mov->medicamento->nombre }}
                    </td>
                    <td class="text-left">
                        {{ $mov->descripcion }}
                    </td>

                    <td>{{ $mov->stock_anterior }}</td>

                    <td>
                        {{ $mov->cantidad }}
                    </td>

                    <td>{{ $mov->stock_actual }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer">
        Documento generado el {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>
