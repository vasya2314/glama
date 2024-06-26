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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('transactionable');
//            $table->foreignId('contract_id')->nullable()->constrained('contracts');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('type');
            $table->string('status');
            $table->string('payment_id')->nullable();
            $table->string('order_id')->nullable();
            $table->integer('amount_deposit');
            $table->integer('amount');
            $table->string('method_type')->index();
            $table->string('balance_account_type', 50)->index();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
