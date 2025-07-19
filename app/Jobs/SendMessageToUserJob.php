<?php

namespace App\Jobs;

use App\Models\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendMessageToUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $pushData;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pushData)
    {
        $this->pushData = $pushData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            Log::debug('inn');

            $registration_ids = (isset($this->pushData['registration_ids'])) ? $this->pushData['registration_ids'] : array();
            $extra_data = (isset($this->pushData['extra_data'])) ? $this->pushData['extra_data'] : array();
            $title = (isset($this->pushData['subject'])) ? $this->pushData['subject'] : '';
            $body_text = (isset($this->pushData['push_text'])) ? $this->pushData['push_text'] : '';

            /* save notification start */
            $this->notification  = new Notification();
            $this->notification->user_id = (isset($this->pushData['user_id'])) ? $this->pushData['user_id']:'';
            $this->notification->user_type = "Customer";
            $this->notification->title = $title;
            $this->notification->description = $body_text;
            $this->notification->is_read = false;
            $this->notification->save();
            /* save notification end */

            $payload = array(
                'sound' => 'default',
                'priority' => 'high',
                "title" => $title,
                "body" => $body_text
            );

            $SERVER_API_KEY = env('FCM_SERVER_KEY');

            $data = [
                "registration_ids" => $registration_ids,
                "notification" => $payload,
                "data" => $extra_data
            ];
            $dataString = json_encode($data);

            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

            $response = curl_exec($ch);
            return $response;
        } catch(\Exception $e){
            \Log::info("push notifcation - ".$e->getMessage());
        }
    }
}
