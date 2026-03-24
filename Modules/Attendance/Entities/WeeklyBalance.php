<?php

namespace Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class WeeklyBalance extends Model
{
    protected $fillable = [
        'user_id',
        'year',
        'week',
        'hours_extra',
        'hours_deficit',
        'closed_at',
    ];

    protected $casts = [
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}