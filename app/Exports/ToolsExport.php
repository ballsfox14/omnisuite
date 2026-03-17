<?php

namespace App\Exports;

use Modules\Inventory\Entities\Tool;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ToolsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Tool::with('accessories')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre',
            'Tipo',
            'Marca',
            'Modelo',
            'Código',
            'Cantidad',
            'Accesorios',
            'Descripción',
            'Creado'
        ];
    }

    public function map($tool): array
    {
        return [
            $tool->id,
            $tool->name,
            $tool->tipo,
            $tool->marca,
            $tool->modelo,
            $tool->code,
            $tool->quantity,
            $tool->accesorios ? implode(', ', $tool->accesorios) : '',
            $tool->description,
            $tool->created_at->format('d/m/Y H:i'),
        ];
    }
}