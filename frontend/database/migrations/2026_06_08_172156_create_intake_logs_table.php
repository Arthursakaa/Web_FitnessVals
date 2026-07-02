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
        Schema::create('intake_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('meal_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('meal_type')->nullable(); // Sarapan, Makan Siang, dll.
            $table->integer('calories');
            $table->integer('protein_g');
            $table->integer('carbs_g');
            $table->integer('fat_g');
            $table->date('log_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intake_logs');
    }
};
