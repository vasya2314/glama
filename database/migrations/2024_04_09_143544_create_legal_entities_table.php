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
        Schema::create('legal_entities', function (Blueprint $table) {
            $table->id();
            $table->string('inn');
            $table->string('kpp');
            $table->string('ogrn');
            $table->string('company_name', 512);
            $table->string('legal_address');
            $table->string('actual_address');
            $table->string('contact_face');
            $table->string('job_title');
            $table->string('phone');
            $table->string('email');
            $table->string('bik');
            $table->string('checking_account');
            $table->string('bank_name');
            $table->string('correspondent_account');
            $table->string('pick_up');
            $table->boolean('is_same_legal_address');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_entities');
    }
};
