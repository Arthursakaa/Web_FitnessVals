<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntakeLog extends Model
{
    protected $fillable = [
        'user_id', 'meal_id', 'name', 'meal_type', 'calories', 
        'protein_g', 'carbs_g', 'fat_g', 'log_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
