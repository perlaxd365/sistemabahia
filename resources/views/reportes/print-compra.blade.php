<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #0b3c5d;
        }

        .portada {
            text-align: center;
            margin-bottom: 30px;
        }

        .portada img {
            width: 100%;
            max-height: 180px;
            object-fit: contain;
        }

        .titulo {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }

        .seccion {
            margin-bottom: 15px;
        }

        .seccion h4 {
            font-size: 13px;
            margin-bottom: 6px;
            border-bottom: 1px solid #0b3c5d;
            padding-bottom: 3px;
        }

        .tabla-info {
            width: 100%;
            border-collapse: collapse;
        }

        .tabla-info td {
            padding: 4px 6px;
        }

        .tabla-detalle {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .tabla-detalle th {
            background: #e3f2fd;
            border: 1px solid #90caf9;
            padding: 6px;
            font-size: 11px;
            text-transform: uppercase;
        }

        .tabla-detalle td {
            border: 1px solid #cfd8dc;
            padding: 6px;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .total {
            margin-top: 10px;
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #546e7a;
        }
    </style>
</head>

<body>

    <!-- PORTADA -->
    <div class="portada">
        <img src="{{ $base64 }}" alt="Logo Clínica">
    </div>

    <div class="titulo">
        Orden de Compra
    </div>

    <!-- DATOS DE COMPRA -->
    <div class="seccion">
        <h4>Datos de la Compra</h4>
        <table class="tabla-info">
            <tr>
                <td><strong>N° Compra:</strong> {{ $compra->id_compra }}</td>
                <td><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($compra->fecha_compra)->format('d/m/Y') }}</td>
            </tr>
            <tr>
                <td><strong>Documento:</strong> {{ $compra->tipo_documento ?? '-' }}</td>
                <td><strong>N° Documento:</strong> {{ $compra->nro_documento ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- PROVEEDOR -->
    <div class="seccion">
        <h4>Datos del Proveedor</h4>
        <table class="tabla-info">
            <tr>
                <td><strong>Proveedor:</strong> {{ $compra->proveedor->razon_social }}</td>
                <td><strong>Ruc:</strong> {{ $compra->proveedor->ruc }}</td>
            </tr>
        </table>
    </div>

    <!-- DETALLE -->
    <div class="seccion">
        <h4>Detalle de Medicamentos</h4>

        <table class="tabla-detalle">
            <thead>
                <tr>
                    <th>Medicamento</th>
                    <th>Cantidad</th>
                    <th>Precio Unit.</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($compra->detalles as $det)
                    <tr>
                        <td>{{ $det->medicamento->nombre }}</td>
                        <td class="text-right">{{ $det->cantidad }}</td>
                        <td class="text-right">S/ {{ number_format($det->precio, 2) }}</td>
                        <td class="text-right">S/ {{ number_format($det->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            TOTAL: S/ {{ number_format($compra->total, 2) }}
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Documento generado por el sistema clínica bahia – {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
