<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Modules\Attendance\Entities\WeeklyBalance;

class User extends Authenticatable
{
    use Notifiable, HasRoles, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'area_id',
        'employee_code',
        'contract_type',
        'weekly_hours',
        'rest_day',
        'initial_balance', // nuevo campo
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'initial_balance' => 'decimal:2',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function weeklyBalances()
    {
        return $this->hasMany(WeeklyBalance::class);
    }

    // Total de horas extra acumuladas por cierres semanales
    public function getTotalCreditAttribute()
    {
        return $this->weeklyBalances()->sum('hours_extra');
    }

    // Total de horas déficit acumuladas por cierres semanales
    public function getTotalDebtAttribute()
    {
        return $this->weeklyBalances()->sum('hours_deficit');
    }

    // Saldo neto total = inicial + (extra - déficit)
    public function getNetBalanceAttribute()
    {
        return $this->initial_balance + ($this->total_credit - $this->total_debt);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'area_id', 'employee_code', 'contract_type', 'weekly_hours', 'rest_day', 'initial_balance'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}