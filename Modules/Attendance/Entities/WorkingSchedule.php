<?php

namespace Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;

class WorkingSchedule extends Model
{
    protected $fillable = [
        'day_of_week',
        'expected_hours',
        'break_required',
    ];

    public $timestamps = true;
}