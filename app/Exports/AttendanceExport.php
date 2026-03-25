<?php

namespace App\Exports;

use Modules\Attendance\Entities\AttendanceRecord;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AttendanceExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $userId;
    protected $startDate;
    protected $endDate;

    public function __construct($userId = null, $startDate = null, $endDate = null)
    {
        $this->userId = $userId;
        $this->startDate = $startDate ? Carbon::parse($startDate) : null;
        $this->endDate = $endDate ? Carbon::parse($endDate) : null;
    }

    public function query()
    {
        $query = AttendanceRecord::with('user');

        if ($this->userId) {
            $query->where('user_id', $this->userId);
        }

        if ($this->startDate) {
            $query->whereDate('date', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->whereDate('date', '<=', $this->endDate);
        }

        return $query->orderBy('date', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Empleado',
            'Fecha',
            'Entrada',
            'Inicio descanso',
            'Fin descanso',
            'Salida',
            'Inicio pausa',
            'Fin pausa',
            'Horas trabajadas',
            'Horas esperadas',
            'Extra',
            'Modo extraordinario',
            'Modo pausa',
            'Estado',
            'Notas'
        ];
    }

    public function map($record): array
    {
        return [
            $record->id,
            $record->user->name,
            $record->date->format('d/m/Y'),
            $record->check_in ? Carbon::parse($record->check_in)->format('h:i A') : '',
            $record->break_start ? Carbon::parse($record->break_start)->format('h:i A') : '',
            $record->break_end ? Carbon::parse($record->break_end)->format('h:i A') : '',
            $record->check_out ? Carbon::parse($record->check_out)->format('h:i A') : '',
            $record->pause_start ? Carbon::parse($record->pause_start)->format('h:i A') : '',
            $record->pause_end ? Carbon::parse($record->pause_end)->format('h:i A') : '',
            $record->formatted_hours,
            sprintf('%d:%02d', floor($record->expected_minutes / 60), $record->expected_minutes % 60),
            $record->formatted_extra,
            $record->extraordinary ? 'Sí' : 'No',
            $record->pause_mode ? 'Sí' : 'No',
            $record->status,
            $record->notes,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}