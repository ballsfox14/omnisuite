<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('working_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('day_of_week'); // 0 domingo, 1 lunes, ..., 6 sábado
            $table->decimal('expected_hours', 4, 2); // horas esperadas (ej. 8.00, 4.00, 0.00)
            $table->boolean('break_required')->default(true); // si el descanso es obligatorio
            $table->timestamps();
        });

        // Insertar valores por defecto
        DB::table('working_schedules')->insert([
            ['day_of_week' => 0, 'expected_hours' => 0, 'break_required' => false], // domingo
            ['day_of_week' => 1, 'expected_hours' => 8, 'break_required' => true], // lunes
            ['day_of_week' => 2, 'expected_hours' => 8, 'break_required' => true], // martes
            ['day_of_week' => 3, 'expected_hours' => 8, 'break_required' => true], // miércoles
            ['day_of_week' => 4, 'expected_hours' => 8, 'break_required' => true], // jueves
            ['day_of_week' => 5, 'expected_hours' => 8, 'break_required' => true], // viernes
            ['day_of_week' => 6, 'expected_hours' => 4, 'break_required' => false], // sábado
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('working_schedules');
    }
};