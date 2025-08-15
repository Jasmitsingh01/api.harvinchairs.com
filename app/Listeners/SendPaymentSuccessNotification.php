<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\PaymentSuccess;
use App\Notifications\PaymentSuccessfulNotification;

class SendPaymentSuccessNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param PaymentSuccess $event
     * @return void
     */
    public function handle(PaymentSuccess $event)
    {
        foreach ($event->order->children as $key => $child_order) {
            $vendor_id = $child_order->shop->owner_id;
            $vendor = User::findOrFail($vendor_id);
            $vendor->notify(new PaymentSuccessfulNotification($event->order));
        }

        $customer = $event->order->customer;
        if (isset($customer)) {
            $customer->notify(new PaymentSuccessfulNotification($event->order));
        }
    }
}
