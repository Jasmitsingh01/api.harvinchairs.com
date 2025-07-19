<?php

namespace App\Listeners;

use App\Events\OrderCancelled;
use App\Models\SiteConfiguration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Traits\EmailTrait;

class SendOrderCancelledAdminNotification
{
    use EmailTrait;
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
     * @param  object  $event
     * @return void
     */
    public function handle(OrderCancelled $event)
    {
        //dd($event->order);
        // send mail to admin for cancel order
        $adminEmail  = SiteConfiguration::where('varname', 'CANCELLATION_ORDER_EMAIL_RECEIVER')->value('value');
        //dd($adminEmail);
        if(!empty($adminEmail)){
            $toIds = explode(',', $adminEmail);

            $order = $event->order;

            $tags = [
                'name' => 'Admin',
                'app_url' => env("APP_URL"),
                "app_name" => env("APP_NAME"),
                "order" => $order,
                "url" => route('admin.orders.show', $order->id),
            ];
            // $toIds=array($adminEmail);
            $this->sendEmailNotification('OrderCancelled', $toIds,$tags);
        }
    }
}
