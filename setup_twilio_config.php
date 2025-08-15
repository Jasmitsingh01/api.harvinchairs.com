<?php

echo "🔧 Twilio Configuration Setup\n";
echo "=============================\n\n";

// Twilio credentials
$twilioCredentials = [
    'TWILIO_ACCOUNT_SID' => 'AC4edae408e6e035a092d0c9c0fedb6169',
    'TWILIO_AUTH_TOKEN' => 'a8944728228ebb4b2b3b9b2decdc5d8e',
    'TWILIO_FROM_NUMBER' => '+18723284628' // You need to replace this with your actual Twilio phone number
];

echo "✅ Your Twilio credentials:\n";
echo "Account SID: {$twilioCredentials['TWILIO_ACCOUNT_SID']}\n";
echo "Auth Token: {$twilioCredentials['TWILIO_AUTH_TOKEN']}\n";
echo "From Number: {$twilioCredentials['TWILIO_FROM_NUMBER']} (⚠️  Replace with your actual Twilio number)\n\n";

echo "📝 Add these lines to your .env file:\n";
echo "=====================================\n";
foreach ($twilioCredentials as $key => $value) {
    echo "{$key}={$value}\n";
}

echo "\n⚠️  Important Notes:\n";
echo "1. Replace TWILIO_FROM_NUMBER with your actual Twilio phone number\n";
echo "2. Make sure your Twilio account has credits\n";
echo "3. The phone number should be in international format (+1234567890)\n";
echo "4. After updating .env, run: php artisan config:clear\n\n";

echo "🧪 To test the configuration:\n";
echo "php test_twilio_sms.php\n\n";

echo "📱 To test with your phone number, update the phone number in test_twilio_sms.php\n";
echo "Current test number: +919818864821\n";
