<?php

namespace App\Listeners;

use App\Models\Notification;
use App\Events\SendNotification;
use App\Models\NotificationEvent;
use App\Jobs\SendMessageToUserJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\NotificationEventTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotificationFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->notification = resolve(Notification::class);
    }

    /**
     * Handle the event.
     *
     * @param  SendNotification  $event
     * @return void
     */
    public function handle(SendNotification $event)
    {
        SendMessageToUserJob::dispatch($event->details);
    }
}
