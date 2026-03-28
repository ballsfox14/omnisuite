<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->text('signature')->nullable(); // almacena la firma como imagen base64
            $table->timestamp('signature_date')->nullable();
            $table->string('signature_token', 100)->nullable()->unique();
            $table->timestamp('signed_at')->nullable();
            $table->enum('signature_method', ['panel', 'public'])->nullable();
        });
    }

    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn(['signature', 'signature_date', 'signature_token', 'signed_at', 'signature_method']);
        });
    }
};