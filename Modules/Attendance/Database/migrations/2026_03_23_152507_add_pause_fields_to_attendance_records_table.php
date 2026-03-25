<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            if (!Schema::hasColumn('attendance_records', 'pause_start')) {
                $table->time('pause_start')->nullable()->after('break_end');
                $table->time('pause_end')->nullable()->after('pause_start');
                $table->boolean('pause_mode')->default(false)->after('extraordinary');
            }
        });
    }

    public function down()
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropColumn(['pause_start', 'pause_end', 'pause_mode']);
        });
    }
};