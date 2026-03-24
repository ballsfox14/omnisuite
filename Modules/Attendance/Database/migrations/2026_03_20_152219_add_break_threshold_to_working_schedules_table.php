<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('working_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('working_schedules', 'break_threshold_minutes')) {
                $table->integer('break_threshold_minutes')->default(360)->after('break_required');
            }
        });
    }

    public function down()
    {
        Schema::table('working_schedules', function (Blueprint $table) {
            $table->dropColumn('break_threshold_minutes');
        });
    }
};