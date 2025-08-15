<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\EmailTrait;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels,EmailTrait;
    private $emailName;
    private $toIds;
    private $tags;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emailName,$toIds,$tags)
    {
        $this->emailName = $emailName;
        $this->toIds = $toIds;
        $this->tags = $tags;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->sendEmailNotification($this->emailName, $this->toIds,$this->tags);
    }
}
