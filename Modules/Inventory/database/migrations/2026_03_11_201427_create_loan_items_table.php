<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('loan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained()->onDelete('cascade');
            $table->morphs('loanable'); // permite asociar a Tool o Kit
            $table->integer('quantity'); // cantidad prestada
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loan_items');
    }
};