<?php

namespace App\Listeners;

use App\Events\SendMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMailFired
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
     * @param  SendMail  $event
     * @return void
     */
    public function handle(SendMail $event)
    {
        Log::info('Event received in listener: ' . get_class($event));
		$data['html'] = $event->mailData['emailContent'];
        Mail::send('emails.email_render', $data, function ($message) use ($event) {
            $message->from($event->mailData['fromEmail'], $event->mailData['fromName']);
            $message->to($event->mailData['toEmail'], $event->mailData['toName']);
            $message->subject($event->mailData['emailSubject']);
            if(isset($event->mailData['attachment']) && !empty($event->mailData['attachment'])){
                $message->attach($event->mailData['attachment']);
            }
            //$message->setBody($event->mailData['emailContent']);
        });
        return true;
    }
}
