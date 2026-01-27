<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comprobante;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public $tipo_pago;
    public $recargo;
    public $porcentaje_recargo_tarjeta;
    //
    public function imprimir(Comprobante $comprobante)
    {
        if ($comprobante->tipo_comprobante !== 'TICKET') {
            abort(404);
        }
        $comprobante->load(['detalles', 'paciente', 'atencion']);
        $pdf = Pdf::loadView(

            'tickets.ticket-venta',
            compact('comprobante')
        )->setPaper([0, 0, 226.77, 600], 'portrait'); // 80mm

        return $pdf->stream(
            'TICKET-' . $comprobante->serie . '-' . $comprobante->numero . '.pdf'
        );
    }
}
