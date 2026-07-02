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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('calories');
            $table->integer('protein_g');
            $table->integer('carbs_g');
            $table->integer('fat_g');
            $table->string('meal_type')->nullable(); // breakfast, lunch, dinner, snack
            $table->string('image_url')->nullable();
            $table->json('dietary_tags')->nullable(); // vegan, vegetarian, halal
            $table->json('medical_tags')->nullable(); // low-sugar, low-sodium
            $table->json('target_workout')->nullable(); // strength, cardio, rest
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
