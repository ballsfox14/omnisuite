<?php

namespace Modules\Attendance\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkingSchedulesSeeder extends Seeder
{
    public function run()
    {
        $schedules = [
            ['day_of_week' => 0, 'expected_hours' => 0, 'break_required' => false, 'break_threshold_minutes' => 0],
            ['day_of_week' => 1, 'expected_hours' => 8, 'break_required' => true, 'break_threshold_minutes' => 360],
            ['day_of_week' => 2, 'expected_hours' => 8, 'break_required' => true, 'break_threshold_minutes' => 360],
            ['day_of_week' => 3, 'expected_hours' => 8, 'break_required' => true, 'break_threshold_minutes' => 360],
            ['day_of_week' => 4, 'expected_hours' => 8, 'break_required' => true, 'break_threshold_minutes' => 360],
            ['day_of_week' => 5, 'expected_hours' => 8, 'break_required' => true, 'break_threshold_minutes' => 360],
            ['day_of_week' => 6, 'expected_hours' => 4, 'break_required' => false, 'break_threshold_minutes' => 0],
        ];

        foreach ($schedules as $schedule) {
            DB::table('working_schedules')->updateOrInsert(
                ['day_of_week' => $schedule['day_of_week']],
                $schedule
            );
        }
    }
}