<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // usuario que solicita el préstamo
            $table->string('borrower_name'); // nombre de la persona que recibe (puede ser el mismo user u otro)
            $table->text('notes')->nullable();
            $table->datetime('loaned_at'); // fecha/hora de salida
            $table->datetime('returned_at')->nullable(); // fecha/hora de retorno (null mientras no se devuelva)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
};