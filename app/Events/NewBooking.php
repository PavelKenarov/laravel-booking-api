<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewBooking
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bookingId;

    /**
     * Create a new event instance.
     * @param $id
     */
    public function __construct($id)
    {
        $this->bookingId = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('new_booking'),
        ];
    }

    public function getBooking()
    {
        return $this->bookingId;
    }
}
