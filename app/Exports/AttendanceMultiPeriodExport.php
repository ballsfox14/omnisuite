<?php

namespace App\Exports;

use Modules\Attendance\Entities\AttendanceRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class AttendanceMultiPeriodExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $userId;
    protected $periods; // array of ['start' => Carbon, 'end' => Carbon]

    public function __construct($userId = null, array $periods = [])
    {
        $this->userId = $userId;
        $this->periods = $periods;
    }

    public function collection()
    {
        $records = collect();

        foreach ($this->periods as $period) {
            $start = $period['start'];
            $end = $period['end'];

            $query = AttendanceRecord::with('user')
                ->whereBetween('date', [$start, $end]);

            if ($this->userId) {
                $query->where('user_id', $this->userId);
            }

            $records = $records->concat($query->get());
        }

        return $records->sortByDesc('date');
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