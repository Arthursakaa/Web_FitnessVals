<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainerBooking extends Model
{
    protected $fillable = [
        'user_id', 'trainer_id', 'booking_date', 'booking_time', 
        'session_type', 'message', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
}
