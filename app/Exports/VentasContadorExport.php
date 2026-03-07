<?php

namespace App\Exports;

use App\Models\Comprobante;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VentasContadorExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles,
    WithTitle
{

    protected $fecha_inicio;
    protected $fecha_fin;
    protected $tipo;

    public function __construct($fecha_inicio, $fecha_fin, $tipo)
    {
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->tipo = $tipo;
    }

    public function collection()
    {
        $ventas = Comprobante::with([
            'cliente',
            'paciente',
            'atencion.medico',
            'detalles',
            'pagos'
        ])

            ->when(
                $this->fecha_inicio,
                fn($q) =>
                $q->whereDate('fecha_emision', '>=', $this->fecha_inicio)
            )

            ->when(
                $this->fecha_fin,
                fn($q) =>
                $q->whereDate('fecha_emision', '<=', $this->fecha_fin)
            )

            ->when(
                $this->tipo === 'BOLETA_FACTURA',
                fn($q) =>
                $q->whereIn('tipo_comprobante', ['BOLETA', 'FACTURA'])
            )

            ->when(
                in_array($this->tipo, ['TICKET', 'BOLETA', 'FACTURA', 'NOTA_CREDITO']),
                fn($q) => $q->where('tipo_comprobante', $this->tipo)
            )

            ->orderBy('fecha_emision', 'desc')
            ->get();

        return $ventas->map(function ($c) {

            return [
                'EMPRESA' => 'CLINICA BAHIA',
                'SERIE' => $c->serie,
                'NUMERO' => $c->numero,
                'FECFAC' => $c->fecha_emision,
                'FECVEN' => $c->fecha_emision,
                'RUC_DNI' => $c->cliente->numero_documento ?? '',
                'RAZON_SOCIAL' => $c->cliente->razon_social ?? '',
                'PACIENTE' => $c->paciente->name ?? '',
                'MEDICO' => $c->atencion->medico->name ?? '',
                'BASE_IMPONIBLE' => $c->subtotal,
                'IGV' => $c->igv,
                'TOTAL' => $c->total,
                'FORMA_PAGO' => $c->metodo_pago ?? '',
                'DETALLE' => $c->detalles
                    ->pluck('descripcion')
                    ->implode(', ')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'EMPRESA',
            'SERIE',
            'NUMERO',
            'FECFAC',
            'FECVEN',
            'RUC_DNI',
            'RAZON_SOCIAL',
            'PACIENTE',
            'MEDICO',
            'BASE IMPONIBLE',
            'IGV',
            'TOTAL',
            'FORMA_PAGO',
            'DETALLE'
        ];
    }

    public function title(): string
    {
        return 'Reporte de Ventas';
    }

    public function styles(Worksheet $sheet)
    {
        return [

            // Encabezado
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '1F4E78']
                ]
            ],

        ];
    }
}
