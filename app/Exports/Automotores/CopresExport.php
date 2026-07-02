<?php

namespace App\Exports\Automotores;

use App\Models\Automotores\Copres;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CopresExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        return Copres::with(['vehiculo', 'creator'])->orderBy('fecha', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Vehículo',
            'Patente',
            'Litros',
            'Precio x Litro',
            'Importe Final',
            'KM Vehículo',
            'KZ (SAP)',
            'Ticket/Factura',
            'CUIT',
            'Original',
            'Salida',
            'Reentrada',
            'Cargado por',
        ];
    }

    public function map($copres): array
    {
        return [
            $copres->fecha?->format('d/m/Y'),
            $copres->vehiculo?->nombre_completo,
            $copres->vehiculo?->patente,
            $copres->litros,
            $copres->precio_x_litro,
            $copres->importe_final,
            $copres->km_vehiculo,
            $copres->kz,
            $copres->numero_ticket_factura,
            $copres->cuit,
            $copres->es_original ? 'Original' : 'Copia',
            $copres->salida?->format('d/m/Y'),
            $copres->reentrada?->format('d/m/Y'),
            $copres->creator?->realname,
        ];
    }
}
