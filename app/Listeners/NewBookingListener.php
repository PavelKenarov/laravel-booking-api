<?php

namespace App\Listeners;

use App\Events\NewBooking;
use App\Models\Booking;
use App\Notifications\BookingNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewBookingListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(NewBooking $event): void
    {
        $booking = $event->getBooking();
        $get = Booking::with('payments', 'room', 'customer')->find($booking->id);

        Log::info('New Booking Created!',  ['booking' => $get]);

        Mail::send('emails.booking',  ['booking' => $get], function ($message) {
            $message->to(config('mail.support_email'))->subject('New booking was created!');
        });
    }
}
