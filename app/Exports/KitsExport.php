<?php

namespace App\Exports;

use Modules\Inventory\Entities\Kit;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KitsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Kit::with('tools')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Código',
            'Herramientas',
            'Descripción',
            'Creado'
        ];
    }

    public function map($kit): array
    {
        $herramientas = $kit->tools->map(function ($tool) {
            return $tool->name . ' (x' . $tool->pivot->quantity . ')';
        })->implode(', ');

        return [
            $kit->id,
            $kit->name,
            $kit->code,
            $herramientas,
            $kit->description,
            $kit->created_at->format('d/m/Y H:i'),
        ];
    }
}