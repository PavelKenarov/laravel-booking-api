<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeBooking implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $oldBooking;
    public $booking;

    /**
     * Create a new event instance.
     *
     * @param Booking $booking
     * @param Booking $oldBooking
     */
    public function __construct(Booking $booking, Booking $oldBooking)
    {
        $this->booking = $booking;
        $this->oldBooking = $oldBooking;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('booking.' . $this->booking->id),
        ];
    }
}
