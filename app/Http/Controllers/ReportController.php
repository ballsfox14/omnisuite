<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\ToolsExport;
use App\Exports\KitsExport;
use App\Exports\LoansExport;
use App\Exports\AttendanceExport;
use App\Exports\AttendanceMultiPeriodExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Inventory\Entities\Tool;
use Modules\Inventory\Entities\Kit;
use Modules\Inventory\Entities\Loan;
use Modules\Attendance\Entities\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'can:ver reportes']);
    }

    public function index()
    {
        return view('reports.index');
    }

    // --- Exportaciones existentes (sin cambios) ---
    public function exportToolsExcel()
    {
        return Excel::download(new ToolsExport, 'herramientas.xlsx');
    }

    public function exportKitsExcel()
    {
        return Excel::download(new KitsExport, 'kits.xlsx');
    }

    public function exportLoansExcel()
    {
        return Excel::download(new LoansExport, 'prestamos.xlsx');
    }

    public function exportToolsPdf()
    {
        $tools = Tool::orderBy('code')->get();
        $pdf = Pdf::loadView('reports.pdf.tools', compact('tools'));
        return $pdf->download('herramientas.pdf');
    }

    public function exportKitsPdf()
    {
        $kits = Kit::with('tools')->orderBy('code')->get();
        $pdf = Pdf::loadView('reports.pdf.kits', compact('kits'));
        return $pdf->download('kits.pdf');
    }

    public function exportLoansPdf()
    {
        $loans = Loan::with(['user', 'items.loanable'])->orderBy('loaned_at', 'desc')->get();
        $pdf = Pdf::loadView('reports.pdf.loans', compact('loans'));
        return $pdf->download('prestamos.pdf');
    }

    // --- Reportes de Asistencia (con acumulación) ---
    public function attendanceForm()
    {
        $users = User::orderBy('name')->get();
        return view('reports.attendance-form', compact('users'));
    }

    public function exportAttendanceExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $userId = $request->user_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $export = new AttendanceExport($userId, $startDate, $endDate);
        return Excel::download($export, 'asistencia_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function exportAttendancePdf(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $userId = $request->user_id;
        $startDate = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $query = AttendanceRecord::with('user')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $records = $query->orderBy('date', 'desc')->get();
        $user = $userId ? User::find($userId) : null;

        $pdf = Pdf::loadView('pdf.attendance', compact('records', 'user', 'startDate', 'endDate'));
        return $pdf->download('asistencia_' . now()->format('Ymd_His') . '.pdf');
    }

    // Búsqueda AJAX (previsualización)
    public function searchAttendance(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $userId = $request->user_id;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $query = AttendanceRecord::with('user')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        $records = $query->orderBy('date', 'asc')->get();

        $totalMinutes = $records->sum('total_minutes');
        $totalHours = floor($totalMinutes / 60);
        $totalMinutesRemainder = $totalMinutes % 60;
        $totalFormatted = sprintf('%d:%02d', $totalHours, $totalMinutesRemainder);

        $data = [
            'records' => $records->map(function ($record) {
                return [
                    'date' => $record->date->format('d/m/Y'),
                    'check_in' => $record->check_in ? Carbon::parse($record->check_in)->format('h:i A') : '-',
                    'break_start' => $record->break_start ? Carbon::parse($record->break_start)->format('h:i A') : '-',
                    'break_end' => $record->break_end ? Carbon::parse($record->break_end)->format('h:i A') : '-',
                    'check_out' => $record->check_out ? Carbon::parse($record->check_out)->format('h:i A') : '-',
                    'hours_worked' => $record->formatted_hours,
                ];
            }),
            'total_hours' => $totalFormatted,
        ];

        return response()->json($data);
    }

    // Exportación con múltiples períodos (Excel)
    public function exportAttendanceMultiPeriodExcel(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'periods' => 'required|json',
        ]);

        $userId = $request->user_id;
        $periodsData = json_decode($request->periods, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors('Los datos de períodos no son válidos.');
        }

        $periods = [];
        foreach ($periodsData as $period) {
            $periods[] = [
                'start' => Carbon::parse($period['start'])->startOfDay(),
                'end' => Carbon::parse($period['end'])->endOfDay(),
            ];
        }

        $export = new AttendanceMultiPeriodExport($userId, $periods);
        return Excel::download($export, 'asistencia_acumulada_' . now()->format('Ymd_His') . '.xlsx');
    }

    // Exportación con múltiples períodos (PDF)
    public function exportAttendanceMultiPeriodPdf(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'periods' => 'required|json',
        ]);

        $userId = $request->user_id;
        $periodsData = json_decode($request->periods, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors('Los datos de períodos no son válidos.');
        }

        $periods = [];
        foreach ($periodsData as $period) {
            $periods[] = [
                'start' => Carbon::parse($period['start'])->startOfDay(),
                'end' => Carbon::parse($period['end'])->endOfDay(),
            ];
        }

        $records = collect();
        foreach ($periods as $period) {
            $query = AttendanceRecord::with('user')
                ->whereBetween('date', [$period['start'], $period['end']]);

            if ($userId) {
                $query->where('user_id', $userId);
            }

            $records = $records->concat($query->get());
        }

        $records = $records->sortByDesc('date');
        $user = $userId ? User::find($userId) : null;

        $pdf = Pdf::loadView('pdf.attendance-multi', compact('records', 'user', 'periods'));
        return $pdf->download('asistencia_acumulada_' . now()->format('Ymd_His') . '.pdf');
    }
}