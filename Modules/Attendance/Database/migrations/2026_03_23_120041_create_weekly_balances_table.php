<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('weekly_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('year');
            $table->integer('week');
            $table->decimal('hours_extra', 5, 2)->default(0);
            $table->decimal('hours_deficit', 5, 2)->default(0);
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'year', 'week']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('weekly_balances');
    }
};