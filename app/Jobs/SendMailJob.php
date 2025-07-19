<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $emailTo;
    private $sendmailClass;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emailTo,$sendmailClass)
    {
        $this->emailTo = $emailTo;
        $this->sendmailClass = $sendmailClass;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->emailTo)->send($this->sendmailClass);
    }
}
