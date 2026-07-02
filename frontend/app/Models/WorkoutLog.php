<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutLog extends Model
{
    protected $guarded = [];

    public function exercises(): HasMany
    {
        return $this->hasMany(WorkoutLogExercise::class);
    }
}
