<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'employee_code')) {
                $table->string('employee_code')->unique()->nullable()->after('id');
            }
            if (!Schema::hasColumn('users', 'contract_type')) {
                $table->enum('contract_type', ['full_time', 'part_time', 'custom'])->default('full_time')->after('area_id');
            }
            if (!Schema::hasColumn('users', 'weekly_hours')) {
                $table->decimal('weekly_hours', 5, 2)->nullable()->after('contract_type');
            }
            if (!Schema::hasColumn('users', 'rest_day')) {
                $table->integer('rest_day')->nullable()->after('weekly_hours');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['employee_code', 'contract_type', 'weekly_hours', 'rest_day']);
        });
    }
};