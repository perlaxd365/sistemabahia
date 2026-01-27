<div>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: monospace;
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

            table {
                width: 100%;
                border-collapse: collapse;
            }

            img {
                max-width: 160px;
            }

            @page {
                size: 80mm auto;
                margin: 0;
            }

            body {
                width: 70mm;
                margin-left: 7%;
                padding-top: 7%;
                font-family: Arial, sans-serif;
                font-size: 12px;
            }
        </style>
    </head>

    <body>
        <div class="center">
            {{-- LOGO --}}
            <img src="{{ public_path('images/logo-clinica.png') }}" alt="Logo">
            <br>
            Ticket de Venta<br>
            <strong>
                {{ $comprobante->serie }}-{{ str_pad($comprobante->numero, 6, '0', STR_PAD_LEFT) }}
            </strong>
        </div>

        <hr>

        Paciente:<br>
        <strong>{{ $comprobante->paciente->name }}</strong><br>
        Fecha: {{ DateUtil::getFechaSimple($comprobante->fecha_emision) }}
        <hr>

        <table>
            @foreach ($comprobante->detalles as $d)
                <tr>
                    <td colspan="2">
                        {{ $d->descripcion }}
                    </td>
                </tr>
                <tr>
                    <td class="left">
                        {{ $d->cantidad }} x {{ number_format($d->precio_unitario, 2) }}
                    </td>
                    <td class="right">
                        {{ number_format($d->subtotal, 2) }}
                    </td>
                </tr>
            @endforeach
        </table>

        <hr>

        @php
            $gravada = $comprobante->subtotal;
            $igv = round($gravada * 0.18, 2);
        @endphp

        <table>
            <tr>
                <td>Gravada</td>
                <td class="right">S/ {{ number_format($comprobante->subtotal, 2) }}</td>
            </tr>
            @if ($comprobante->con_igv)
                <tr>
                    <td>IGV (18%)</td>
                    <td class="right">S/ {{ number_format($comprobante->igv, 2) }}</td>
                </tr>
            @endif
            @if ($comprobante->recargo > 0)
                <tr>
                    <td>Recargo tarjeta (5%):</td>
                    <td class="right">S/ {{ number_format($comprobante->recargo, 2) }}</td>
                </tr>
            @endif
            <tr>
                <td><strong>TOTAL</strong></td>
                <td class="right">
                    @if ($comprobante->total_cobrado)
                        <strong>S/ {{ number_format($comprobante->total_cobrado, 2) }}</strong>
                    @else
                        <strong>S/ {{ number_format($comprobante->total, 2) }}</strong>
                    @endif
                </td>
            </tr>
        </table>

        <hr>

        <div class="center">
            <strong>Gracias por su preferencia</strong><br>

            Clinica Bahía Trabajando por tu salud
        </div>

    </body>

    </html>

</div>
