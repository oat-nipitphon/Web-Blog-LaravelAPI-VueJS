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
        Schema::create('user_profile_pops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->nullable();
            $table->foreignId('profile_id_pop')->nullable();
            $table->enum('status', ['null', 'true', 'false'])->default('null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_pops');
    }
};
