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
        Schema::create('transaction_loan_detail', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->references('transaction_id')->on('transaction_loan')->onDelete('cascade');
            $table->integer('gl_code')->references('gl_code')->on('master_gl_code');
            $table->integer('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_loan_detail');
    }
};
