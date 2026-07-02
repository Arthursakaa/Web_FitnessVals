<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GymClass extends Model
{
    protected $guarded = [];

    public function schedules(): HasMany
    {
        return $this->hasMany(ClassSchedule::class);
    }
}
