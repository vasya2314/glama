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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('contract_id')->unique()->nullable()->constrained('contracts')->onDelete('set null');
            $table->string('account_name', 50);
            $table->string('login', 50)->unique();
            $table->string('password');
            $table->unsignedBigInteger('client_id')->unique();
            $table->integer('qty_campaigns')->default(0);
            $table->boolean('is_enable_shared_account')->default(false);
            $table->bigInteger('balance')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
