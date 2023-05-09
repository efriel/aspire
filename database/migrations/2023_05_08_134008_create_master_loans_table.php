<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('master_loan', function (Blueprint $table) {
            $table->id();
            $table->string('account_name');
            $table->integer('limit');
            $table->integer('tenor');
            $table->integer('gl_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_loan');
    }
};
