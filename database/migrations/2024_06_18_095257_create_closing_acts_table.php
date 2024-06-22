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
        Schema::create('closing_acts', function (Blueprint $table) {
            $table->id();
            $table->string('act_number', 200)->nullable();
            $table->foreignId('contract_id')->constrained('contracts');
            $table->dateTime('date_generated');
            $table->bigInteger('amount');
            $table->bigInteger('amount_nds');
            $table->string('path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('closing_acts');
    }
};
