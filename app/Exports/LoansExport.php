<?php

namespace App\Exports;

use Modules\Inventory\Entities\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LoansExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Loan::with(['user', 'items.loanable'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Responsable',
            'Elementos',
            'Fecha préstamo',
            'Fecha devolución',
            'Estado',
            'Observaciones'
        ];
    }

    public function map($loan): array
    {
        $elementos = $loan->items->map(function ($item) {
            $nombre = $item->loanable->name ?? 'N/A';
            return $nombre . ' (x' . $item->quantity . ')';
        })->implode(', ');

        return [
            $loan->id,
            $loan->user->name ?? $loan->borrower_name ?? 'N/A',
            $elementos,
            $loan->loaned_at->format('d/m/Y'),
            $loan->returned_at ? $loan->returned_at->format('d/m/Y') : 'Pendiente',
            $loan->returned_at ? 'Devuelto' : 'Prestado',
            $loan->notes,
        ];
    }
}