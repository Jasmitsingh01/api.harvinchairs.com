<?php

namespace App\Listeners;

use App\Database\Models\User;
use App\Mail\EnquiryNotification;
use Illuminate\Support\Facades\Mail;
use App\Events\EnquirySubmittedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEnquiryNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\InquirySubmittedEvent  $event
     * @return void
     */
    public function handle(EnquirySubmittedEvent $event)
    {
        $inquiry = $event->inquiry;
        // Add any logic to process the inquiry here
        // For example, sending an email notification
        Mail::to($inquiry->customer_email)->send(new EnquiryNotification($inquiry));
    }
}
