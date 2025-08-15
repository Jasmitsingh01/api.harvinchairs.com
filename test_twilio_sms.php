<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Otp\Gateways\TwilioSmsGateway;

try {
    echo "Testing Twilio SMS Gateway...\n";
    
    // Check if Twilio credentials are configured
    $accountSid = config('services.twilio.account_sid');
    $authToken = config('services.twilio.auth_token');
    $fromNumber = config('services.twilio.from_number');
    
         if (!$accountSid || !$authToken || !$fromNumber) {
         echo "❌ Twilio credentials not configured!\n";
         echo "Please add the following to your .env file:\n";
         echo "TWILIO_ACCOUNT_SID=AC4edae408e6e035a092d0c9c0fedb6169\n";
         echo "TWILIO_AUTH_TOKEN=a8944728228ebb4b2b3b9b2decdc5d8e\n";
         echo "TWILIO_FROM_NUMBER=your_twilio_phone_number\n";
         exit(1);
     }
    
    echo "✅ Twilio credentials found\n";
    echo "Account SID: " . substr($accountSid, 0, 10) . "...\n";
    echo "From Number: {$fromNumber}\n";
    
    // Test phone number (replace with your actual phone number for testing)
    $testPhoneNumber = '+919818864821'; // Replace with actual phone number
    
    echo "\nTesting SMS sending to: {$testPhoneNumber}\n";
    echo "Note: Replace the phone number in the script with your actual number for testing\n";
    
    // Create Twilio SMS Gateway instance
    $twilioGateway = new TwilioSmsGateway();
    
    // Send test SMS
    $result = $twilioGateway->startVerification($testPhoneNumber);
    
    if ($result->isValid()) {
        echo "✅ SMS sent successfully!\n";
        $data = $result->getId();
        if (is_string($data)) {
            echo "Message SID: " . $data . "\n";
        } else {
            echo "Result: " . $data . "\n";
        }
    } else {
        echo "❌ SMS sending failed: " . implode(', ', $result->getErrors()) . "\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
