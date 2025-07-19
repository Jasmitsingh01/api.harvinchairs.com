<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailTestController extends Controller
{
    public function sendTestMail()
    {
        // dd(config('mail'));
        // dd(config('mail'));
        try {
            // Mail::raw('This is a test email.', function ($message) {
            //     $message->to('shradhha.indapoint@gmail.com')
            //             ->subject('Test Email');
            // });
            $data['html'] = 'Test Mail Content';
            Mail::send('emails.email_render', $data, function ($message) {
                $message->from(config('mail.from.address'), config('mail.from.name'));
                $message->to('shradhha.indapoint@gmail.com', 'shraddha');
                $message->subject('New registration on Harvin');
                // if(isset($event->mailData['attachment']) && !empty($event->mailData['attachment'])){
                //     $message->attach($event->mailData['attachment']);
                // }
                //$message->setBody($event->mailData['emailContent']);
            });
            return 'Test email sent!';
        } catch (\Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }
}
