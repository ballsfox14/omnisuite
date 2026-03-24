<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Attendance\Entities\AttendanceRecord;
use Modules\Attendance\Entities\WeeklyBalance;
use Carbon\Carbon;
use App\Models\User;

class CloseWeeklyAttendance extends Command
{
    protected $signature = 'attendance:close-week';
    protected $description = 'Cierra la semana anterior de asistencia y guarda el balance';

    public function handle()
    {
        // Calcular la semana anterior (de lunes a domingo)
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek(Carbon::MONDAY);
        $lastWeekEnd = $lastWeekStart->copy()->endOfWeek(Carbon::SUNDAY);
        $year = $lastWeekStart->year;
        $week = $lastWeekStart->weekOfYear;

        $this->info("Cerrando semana $week/$year (del {$lastWeekStart->toDateString()} al {$lastWeekEnd->toDateString()})");

        // Obtener todos los usuarios que tuvieron registros en esa semana
        $userIds = AttendanceRecord::whereBetween('date', [$lastWeekStart, $lastWeekEnd])
            ->distinct()
            ->pluck('user_id');

        $closedCount = 0;
        foreach ($userIds as $userId) {
            // Verificar si ya está cerrada
            $exists = WeeklyBalance::where('user_id', $userId)
                ->where('year', $year)
                ->where('week', $week)
                ->exists();
            if ($exists) {
                $this->line("Usuario $userId: ya cerrada.");
                continue;
            }

            // Calcular balance
            $records = AttendanceRecord::where('user_id', $userId)
                ->whereBetween('date', [$lastWeekStart, $lastWeekEnd])
                ->get();

            $netMinutes = 0;
            foreach ($records as $record) {
                $expected = $record->expected_minutes;
                $worked = $record->total_minutes ?? 0;
                $netMinutes += ($worked - $expected);
            }
            $netHours = round($netMinutes / 60, 2);

            $hoursExtra = $netHours > 0 ? $netHours : 0;
            $hoursDeficit = $netHours < 0 ? abs($netHours) : 0;

            WeeklyBalance::create([
                'user_id' => $userId,
                'year' => $year,
                'week' => $week,
                'hours_extra' => $hoursExtra,
                'hours_deficit' => $hoursDeficit,
                'closed_at' => Carbon::now(),
            ]);

            $this->info("Usuario $userId: balance {$netHours} h");
            $closedCount++;
        }

        $this->info("Cierre completado. $closedCount registros guardados.");
        return 0;
    }
}