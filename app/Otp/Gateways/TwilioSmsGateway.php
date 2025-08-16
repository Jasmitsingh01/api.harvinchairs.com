<?php

namespace App\Otp\Gateways;

use App\Otp\OtpInterface;
use App\Otp\Result;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioSmsGateway implements OtpInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $from_number;

    /**
     * TwilioSmsGateway constructor.
     * @param $client
     * @param string|null $from_number
     * @throws \Twilio\Exceptions\ConfigurationException
     */
    public function __construct($client = null, string $from_number = null)
    {
        if ($client === null) {
            $sid = config('services.twilio.account_sid');
            $token = config('services.twilio.auth_token');
            $client = new Client($sid, $token);
        }
        $this->client = $client;
        $this->from_number = $from_number ?: config('services.twilio.from_number');
    }

    /**
     * Send OTP via SMS using Twilio
     *
     * @param $phone_number
     * @param $otp (optional) - if not provided, will generate one
     * @return Result
     */
    public function startVerification($phone_number, $otp = null)
    {
        try {
            // Use provided OTP or generate one if not provided
            if ($otp === null) {
                $otp = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
            }
            
            // Send SMS with OTP
            $message = $this->client->messages->create(
                $phone_number,
                [
                    'from' => $this->from_number,
                    'body' => "Your verification code is: {$otp}. Valid for 15 minutes."
                ]
            );

            // Return the message SID for tracking
            return new Result($message->sid);
        } catch (TwilioException $exception) {
            return new Result(["SMS sending failed: {$exception->getMessage()}"]);
        }
    }

    /**
     * Check verification code
     *
     * @param $id
     * @param $code
     * @param $phone_number
     * @return Result
     */
    public function checkVerification($id, $code, $phone_number)
    {
        try {
            // Check OTP against stored value in database
            $phoneVerification = \App\Models\PhoneVerification::where('phone_number', $phone_number)
                ->where('otp', $code)
                ->first();
            
            if ($phoneVerification) {
                // Check if OTP is not expired (15 minutes)
                $current_time = \Carbon\Carbon::now()->timestamp;
                $created_time = $phoneVerification->updated_at->timestamp;
                
                if (($current_time - $created_time) <= 900) { // 15 minutes = 900 seconds
                    // Mark as verified
                    $phoneVerification->is_verified = 'Yes';
                    $phoneVerification->save();
                    return new Result($phoneVerification->id);
                } else {
                    return new Result(['OTP has expired']);
                }
            }
            
            return new Result(['Invalid OTP code']);
        } catch (TwilioException $exception) {
            return new Result(["Verification check failed: {$exception->getMessage()}"]);
        }
    }
}
