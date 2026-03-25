<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // ← Importa la fachada Schedule

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Programación del cierre semanal de asistencia
Schedule::command('attendance:close-week')->weeklyOn(1, '00:05');