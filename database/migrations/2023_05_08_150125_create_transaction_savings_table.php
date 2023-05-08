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
        Schema::create('transaction_savings', function (Blueprint $table) {
            $table->integer('transaction_id')->primary();
            $table->integer('account_number')->references('account_number')->on('account_savings');
            $table->integer('customer_id')->references('customer_id')->on('customer');
            $table->integer('total');
            $table->integer('staff_id');
            $table->string('code')->references('code')->on('master_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_savings');
    }
};
