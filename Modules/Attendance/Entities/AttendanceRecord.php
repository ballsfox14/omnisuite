<?php

namespace Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class AttendanceRecord extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'break_start',
        'break_end',
        'check_out',
        'total_minutes',
        'status',
        'notes',
        'extraordinary',
        'pause_mode',
        'pause_start',
        'pause_end',
    ];

    protected $casts = [
        'date' => 'date',
        'extraordinary' => 'boolean',
        'pause_mode' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calculateMinutes()
    {
        if (!$this->check_in || !$this->check_out) {
            return null;
        }

        $checkIn = strtotime($this->check_in);
        $checkOut = strtotime($this->check_out);
        if ($checkOut < $checkIn) {
            $checkOut += 24 * 3600;
        }

        $totalSeconds = $checkOut - $checkIn;
        $totalMinutes = round($totalSeconds / 60);

        // Si hay descanso marcado (inicio y fin)
        if ($this->break_start && $this->break_end) {
            $breakStart = strtotime($this->break_start);
            $breakEnd = strtotime($this->break_end);
            if ($breakEnd > $breakStart) {
                $breakMinutes = round(($breakEnd - $breakStart) / 60);
                $totalMinutes -= $breakMinutes;
            }
            // No se aplica descanso automático si se marcó
        } else {
            // No hay descanso marcado: aplicar reglas de descanso obligatorio solo si no está en modo pausa
            $dayOfWeek = $this->date->dayOfWeek;
            $schedule = WorkingSchedule::where('day_of_week', $dayOfWeek)->first();

            if (!$this->pause_mode && $schedule && $schedule->break_required) {
                $threshold = $schedule->break_threshold_minutes;
                if ($totalMinutes >= $threshold) {
                    $totalMinutes -= 60; // Restar 1 hora
                }
            }
        }

        // Restar el tiempo de pausa si existe (siempre)
        if ($this->pause_start && $this->pause_end) {
            $pauseStart = strtotime($this->pause_start);
            $pauseEnd = strtotime($this->pause_end);
            if ($pauseEnd > $pauseStart) {
                $pauseMinutes = round(($pauseEnd - $pauseStart) / 60);
                $totalMinutes -= $pauseMinutes;
            }
        }

        return max(0, $totalMinutes);
    }

    public function getExpectedMinutesAttribute()
    {
        if ($this->user->rest_day === $this->date->dayOfWeek) {
            return 0;
        }

        $schedule = WorkingSchedule::where('day_of_week', $this->date->dayOfWeek)->first();
        if (!$schedule) {
            return 0;
        }

        return $schedule->expected_hours * 60;
    }

    public function getExtraMinutesAttribute()
    {
        if ($this->total_minutes === null || $this->expected_minutes === null) {
            return 0;
        }
        return max(0, $this->total_minutes - $this->expected_minutes);
    }

    public function getFormattedHoursAttribute()
    {
        if ($this->total_minutes === null) {
            return '—';
        }
        $hours = floor($this->total_minutes / 60);
        $minutes = $this->total_minutes % 60;
        return sprintf('%d:%02d', $hours, $minutes);
    }

    public function getFormattedExtraAttribute()
    {
        $minutes = $this->extra_minutes;
        if (!$minutes)
            return '—';
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return sprintf('%d:%02d', $hours, $mins);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($record) {
            $record->total_minutes = $record->calculateMinutes();
        }); 
    }
}