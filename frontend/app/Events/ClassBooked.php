<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClassBooked implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $scheduleId;
    public $currentBookings;
    public $maxCapacity;

    /**
     * Create a new event instance.
     */
    public function __construct($scheduleId, $currentBookings, $maxCapacity)
    {
        $this->scheduleId = $scheduleId;
        $this->currentBookings = $currentBookings;
        $this->maxCapacity = $maxCapacity;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('gym-classes'),
        ];
    }
}
