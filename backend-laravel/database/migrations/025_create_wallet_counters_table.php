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
        Schema::create('wallet_counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->nullable();
            $table->foreignId('reward_id')->nullable();
            $table->double('point')->nullable();
            $table->double('amount')->nullable();
            $table->enum('status', ['null', 'in', 'out'])->default('null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_counters');
    }
};
