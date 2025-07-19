<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use App\Events\BankWireOrderProcessed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\BankWireOrderProcessedNotification;

class SendBankWireOrderNotification
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
     * @param  \App\Events\BankWireOrderProcessed  $event
     * @return void
     */
    public function handle(BankWireOrderProcessed $event)
    {
        $order = $event->order;
        // Add any logic to process the inquiry here
        // For example, sending an email notification
        Mail::to($order->customer->email)->send(new BankWireOrderProcessedNotification($order));
    }
}
