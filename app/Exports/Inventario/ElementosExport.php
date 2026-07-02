<?php

namespace App\Exports\Inventario;

use App\Models\Inventario\Elemento;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ElementosExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    use Exportable;

    public function collection()
    {
        return Elemento::with(['categoria', 'estado', 'user', 'sede', 'area'])->get();
    }

    public function headings(): array
    {
        return [
            'Código',
            'Categoría',
            'Estado',
            'Nombre',
            'Número de serie',
            'Usuario asignado',
            'Sede',
            'Área',
        ];
    }

    public function map($elemento): array
    {
        return [
            $elemento->codigo,
            $elemento->categoria?->nombre,
            $elemento->estado?->nombre,
            $elemento->nombre,
            $elemento->numero_serie,
            $elemento->user?->realname,
            $elemento->sede?->nombre,
            $elemento->area?->nombre,
        ];
    }
}
