<?php

namespace App\Listeners;

use App\Events\ChangeBooking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ChangeBookingListener
{

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     * @param ChangeBooking $event
     */
    public function handle(ChangeBooking $event): void
    {
        $data = [
            'booking' => $event->booking,
            'changes' => $event->booking->getChanges(),
            'oldBooking' => $event->oldBooking
        ];

        Log::info('The booking has been changed!',  $data);

        Mail::send('emails.change_booking',  $data, function ($message) {
            $message->to(config('mail.support_email'))->subject('The booking has been changed!');
        });
    }
}
