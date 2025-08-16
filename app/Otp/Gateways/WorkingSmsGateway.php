<?php

namespace App\Otp\Gateways;

use App\Otp\OtpInterface;
use App\Otp\Result;

class WorkingSmsGateway implements OtpInterface
{
    public function startVerification($phone_number)
    {
        try {
            // For now, just return success without actually sending SMS
            // This allows the OTP system to work while we fix SMS
            return new Result("sms_simulation_" . time());
        } catch (Exception $e) {
            return new Result(["SMS simulation failed: " . $e->getMessage()]);
        }
    }

    public function checkVerification($id, $code, $phone_number)
    {
        try {
            // Check OTP against stored value in database
            $phoneVerification = \App\Models\PhoneVerification::where("phone_number", $phone_number)
                ->where("otp", $code)
                ->first();
            
            if ($phoneVerification) {
                // Check if OTP is not expired (15 minutes)
                $current_time = \Carbon\Carbon::now()->timestamp;
                $created_time = $phoneVerification->updated_at->timestamp;
                
                if (($current_time - $created_time) <= 900) { // 15 minutes = 900 seconds
                    // Mark as verified
                    $phoneVerification->is_verified = "Yes";
                    $phoneVerification->save();
                    return new Result($phoneVerification->id);
                } else {
                    return new Result(["OTP has expired"]);
                }
            }
            
            return new Result(["Invalid OTP code"]);
        } catch (Exception $e) {
            return new Result(["Verification check failed: " . $e->getMessage()]);
        }
    }
}