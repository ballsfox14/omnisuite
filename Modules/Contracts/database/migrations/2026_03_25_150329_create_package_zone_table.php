<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('package_zone', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2); // precio específico para ese paquete en esa zona
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_zone');
    }
};