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
        Schema::create('account_loan', function (Blueprint $table) {
            $table->integer('account_number')->primary();
            $table->integer('account_type_id')->references('id')->on('master_loan');
            $table->integer('customer_id')->references('customer_id')->on('customer');
            $table->integer('day_term');
            $table->string('code')->references('code')->on('master_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_loan');
    }
};
