<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meal extends Model
{
    protected $fillable = [
        'name', 'description', 'calories', 'protein_g', 'carbs_g', 'fat_g',
        'meal_type', 'image_url', 'dietary_tags', 'medical_tags', 'target_workout'
    ];

    protected function casts(): array
    {
        return [
            'dietary_tags' => 'array',
            'medical_tags' => 'array',
            'target_workout' => 'array',
        ];
    }
}
