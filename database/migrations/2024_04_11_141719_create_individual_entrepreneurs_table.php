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
        Schema::create('individual_entrepreneurs', function (Blueprint $table) {
            $table->id();
            $table->string('inn')->nullable();
            $table->string('ogrnip')->nullable();
            $table->string('company_name', 512)->nullable();
            $table->string('legal_address')->nullable();
            $table->string('actual_address')->nullable();
            $table->string('contact_face')->nullable();
            $table->string('job_title')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('bik')->nullable();
            $table->string('checking_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('correspondent_account')->nullable();
            $table->string('pick_up')->nullable();
            $table->boolean('is_same_legal_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('individual_entrepreneurs');
    }
};
