<?php

namespace Modules\Attendance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Attendance\Entities\AttendanceRecord;
use Modules\Attendance\Entities\WorkingSchedule;
use Modules\Attendance\Entities\WeeklyBalance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function markForm()
    {
        $today = Carbon::today()->toDateString();
        $record = AttendanceRecord::where('user_id', auth()->id())
            ->where('date', $today)
            ->first();

        $switchDisabled = $record && $record->check_in !== null;
        $extraordinary = $record ? $record->extraordinary : false;
        $pauseMode = $record ? $record->pause_mode : false;

        $weekData = $this->getCurrentWeekData(auth()->id());
        $weeklyHistory = $this->getWeeklyHistory(auth()->id());

        return view('attendance::mark', compact('record', 'switchDisabled', 'extraordinary', 'pauseMode', 'weekData', 'weeklyHistory'));
    }

    public function index()
    {
        $records = AttendanceRecord::with('user')->latest('date')->paginate(20);
        return view('attendance::index', compact('records'));
    }

    private function markEvent($field, $errorMsg, $requiredField = null)
    {
        $today = Carbon::today()->toDateString();
        $userId = auth()->id();

        $record = AttendanceRecord::where('user_id', $userId)->where('date', $today)->first();

        if (!$record && $field === 'check_in') {
            $record = new AttendanceRecord();
            $record->user_id = $userId;
            $record->date = $today;
        }

        if (!$record) {
            return back()->withErrors(['error' => 'Debes marcar entrada primero.']);
        }

        if ($record->$field) {
            return back()->withErrors(['error' => $errorMsg]);
        }

        if ($requiredField && !$record->$requiredField) {
            return back()->withErrors(['error' => "Debes marcar el paso anterior primero."]);
        }

        if ($field === 'check_in') {
            $extraordinary = request()->has('extraordinary') && request()->input('extraordinary') == '1';
            $pauseMode = request()->has('pause_mode') && request()->input('pause_mode') == '1';

            // Exclusión mutua: no pueden estar ambos activos
            if ($extraordinary && $pauseMode) {
                $pauseMode = false;
            }

            $record->extraordinary = auth()->user()->can('usar modo extraordinario') ? $extraordinary : false;
            $record->pause_mode = auth()->user()->can('usar modo pausa') ? $pauseMode : false;
        }

        $record->$field = Carbon::now()->format('H:i:s');
        $record->save();

        return redirect()->route('attendance.mark.form')->with('success', 'Marcado correcto.');
    }

    public function markCheckIn()
    {
        return $this->markEvent('check_in', 'Ya marcaste entrada.');
    }
    public function markBreakStart()
    {
        return $this->markEvent('break_start', 'Ya marcaste inicio descanso.', 'check_in');
    }
    public function markBreakEnd()
    {
        return $this->markEvent('break_end', 'Ya marcaste fin descanso.', 'break_start');
    }

    public function markCheckOut()
    {
        $today = Carbon::today()->toDateString();
        $userId = auth()->id();
        $record = AttendanceRecord::where('user_id', $userId)->where('date', $today)->first();

        if (!$record) {
            return redirect()->back()->withErrors(['error' => 'Debes marcar entrada primero.']);
        }
        if ($record->check_out) {
            return redirect()->back()->withErrors(['error' => 'Ya marcaste salida.']);
        }

        if (!$record->extraordinary && !$record->pause_mode) {
            if (!$record->break_start || !$record->break_end) {
                return redirect()->back()->withErrors(['error' => 'Debes marcar inicio y fin de descanso antes de salir.']);
            }
        }

        $record->check_out = Carbon::now()->format('H:i:s');
        $record->save();
        $record->refresh();

        $trabajado = $record->formatted_hours;
        $esperado = sprintf('%d:%02d', floor($record->expected_minutes / 60), $record->expected_minutes % 60);
        $extra = $record->formatted_extra;

        $tipoJornada = match ($record->user->contract_type) {
            'full_time' => 'Tiempo completo',
            'part_time' => 'Medio tiempo',
            'custom' => 'Personalizado',
            default => '—',
        };

        session()->flash('modal_data', [
            'titulo' => 'Jornada finalizada',
            'trabajado' => $trabajado,
            'esperado' => $esperado,
            'extra' => $extra,
            'tipo_jornada' => $tipoJornada,
        ]);

        return redirect()->route('attendance.mark.form')->with('success', 'Salida registrada.');
    }

    public function markPauseStart()
    {
        $today = Carbon::today()->toDateString();
        $userId = auth()->id();
        $record = AttendanceRecord::where('user_id', $userId)->where('date', $today)->first();

        if (!$record || !$record->check_in) {
            return redirect()->back()->withErrors(['error' => 'Debes marcar entrada primero.']);
        }
        if ($record->pause_start) {
            return redirect()->back()->withErrors(['error' => 'Ya has iniciado una pausa.']);
        }
        if ($record->pause_end) {
            return redirect()->back()->withErrors(['error' => 'La pausa ya ha finalizado.']);
        }

        $record->pause_start = Carbon::now()->format('H:i:s');
        $record->save();

        return redirect()->route('attendance.mark.form')->with('success', 'Pausa iniciada.');
    }

    public function markPauseEnd()
    {
        $today = Carbon::today()->toDateString();
        $userId = auth()->id();
        $record = AttendanceRecord::where('user_id', $userId)->where('date', $today)->first();

        if (!$record || !$record->check_in) {
            return redirect()->back()->withErrors(['error' => 'Debes marcar entrada primero.']);
        }
        if (!$record->pause_start) {
            return redirect()->back()->withErrors(['error' => 'Debes iniciar la pausa primero.']);
        }
        if ($record->pause_end) {
            return redirect()->back()->withErrors(['error' => 'La pausa ya ha finalizado.']);
        }

        $record->pause_end = Carbon::now()->format('H:i:s');
        $record->save();

        return redirect()->route('attendance.mark.form')->with('success', 'Pausa finalizada.');
    }

    // --- Métodos para balance semanal ---
    private function getCurrentWeekData($userId)
    {
        $now = Carbon::now();
        $startOfWeek = $now->copy()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $now->copy()->endOfWeek(Carbon::SUNDAY);

        $dates = [];
        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $dates[] = $date->copy();
        }

        $records = AttendanceRecord::where('user_id', $userId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get()
            ->keyBy(function ($item) {
                return $item->date->toDateString();
            });

        $weekData = [];
        $netBalance = 0;
        foreach ($dates as $date) {
            $record = $records->get($date->toDateString());
            $expected = $record ? $record->expected_minutes : 0;
            $worked = $record ? $record->total_minutes : 0;

            $diffMinutes = $worked - $expected;
            $diffHours = round($diffMinutes / 60, 2);
            $netBalance += $diffHours;

            $weekData[] = [
                'date' => $date,
                'expected' => $expected,
                'worked' => $worked,
                'diff_hours' => $diffHours,
            ];
        }

        return [
            'week_data' => $weekData,
            'net_balance' => $netBalance,
            'start_of_week' => $startOfWeek,
            'end_of_week' => $endOfWeek,
        ];
    }

    private function getWeeklyHistory($userId)
    {
        return WeeklyBalance::where('user_id', $userId)
            ->orderBy('year', 'desc')
            ->orderBy('week', 'desc')
            ->get();
    }

    public function closeWeek(Request $request)
    {
        $user = auth()->user();
        $year = $request->input('year', Carbon::now()->year);
        $week = $request->input('week', Carbon::now()->weekOfYear);

        $exists = WeeklyBalance::where('user_id', $user->id)
            ->where('year', $year)
            ->where('week', $week)
            ->exists();
        if ($exists) {
            return redirect()->route('attendance.mark.form')
                ->withErrors(['error' => "La semana $week/$year ya está cerrada."]);
        }

        $balance = $this->calculateWeekBalance($user->id, $year, $week);
        if ($balance === null) {
            return redirect()->route('attendance.mark.form')
                ->withErrors(['error' => "No hay registros para la semana $week/$year."]);
        }

        $hoursExtra = $balance > 0 ? $balance : 0;
        $hoursDeficit = $balance < 0 ? abs($balance) : 0;

        WeeklyBalance::create([
            'user_id' => $user->id,
            'year' => $year,
            'week' => $week,
            'hours_extra' => $hoursExtra,
            'hours_deficit' => $hoursDeficit,
            'closed_at' => Carbon::now(),
        ]);

        return redirect()->route('attendance.mark.form')
            ->with('success', "Semana $week/$year cerrada correctamente. Balance: " . ($balance > 0 ? "+$balance" : $balance) . " horas.");
    }

    private function calculateWeekBalance($userId, $year, $week)
    {
        $startOfWeek = Carbon::now()->setISODate($year, $week)->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $startOfWeek->copy()->endOfWeek(Carbon::SUNDAY);

        $records = AttendanceRecord::where('user_id', $userId)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->get();

        if ($records->isEmpty()) {
            return null;
        }

        $netMinutes = 0;
        foreach ($records as $record) {
            $expected = $record->expected_minutes;
            $worked = $record->total_minutes ?? 0;
            $netMinutes += ($worked - $expected);
        }

        return round($netMinutes / 60, 2);
    }

    public function adminBalance(Request $request)
    {
        $employees = User::orderBy('name')->get();
        $selectedUserId = $request->input('user_id', auth()->id());
        $user = User::findOrFail($selectedUserId);

        $weekData = $this->getCurrentWeekData($selectedUserId);
        $weeklyHistory = $this->getWeeklyHistory($selectedUserId);

        $dailyRecord = AttendanceRecord::where('user_id', $selectedUserId)
            ->where('date', Carbon::today())
            ->first();

        return view('attendance::admin_balance', compact('employees', 'user', 'weekData', 'weeklyHistory', 'dailyRecord'));
    }
}