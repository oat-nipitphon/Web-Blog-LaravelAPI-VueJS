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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profile_id')->constrained('user_profiles')->onDelete('cascade');
            $table->foreignId('type_id')->nullable();
            $table->text('title');
            $table->text('content');
            $table->string('refer')->nullable();
            $table->enum('status', ['active', 'store', 'disable', 'null'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
