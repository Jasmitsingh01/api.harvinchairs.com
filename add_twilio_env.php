<?php

echo "🔧 Adding Twilio credentials to .env file...\n";
echo "==========================================\n\n";

// Twilio credentials
$twilioConfig = [
    'TWILIO_ACCOUNT_SID' => 'AC4edae408e6e035a092d0c9c0fedb6169',
    'TWILIO_AUTH_TOKEN' => 'a8944728228ebb4b2b3b9b2decdc5d8e',
    'TWILIO_FROM_NUMBER' => '+18723284628'
];

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    echo "❌ .env file not found!\n";
    echo "Please create a .env file in your project root.\n";
    exit(1);
}

// Read current .env file
$envContent = file_get_contents($envFile);

// Check if Twilio config already exists
$updated = false;
foreach ($twilioConfig as $key => $value) {
    if (strpos($envContent, $key . '=') === false) {
        // Add the configuration
        $envContent .= "\n# Twilio Configuration\n";
        $envContent .= "{$key}={$value}\n";
        $updated = true;
        echo "✅ Added {$key}={$value}\n";
    } else {
        echo "⚠️  {$key} already exists in .env file\n";
    }
}

if ($updated) {
    // Write back to .env file
    file_put_contents($envFile, $envContent);
    echo "\n✅ Twilio credentials added to .env file!\n";
    echo "Now run: php artisan config:clear\n";
    echo "Then test with: php test_twilio_sms.php\n";
} else {
    echo "\n✅ All Twilio credentials already exist in .env file!\n";
}

echo "\n📱 Your Twilio Configuration:\n";
echo "Account SID: {$twilioConfig['TWILIO_ACCOUNT_SID']}\n";
echo "Auth Token: {$twilioConfig['TWILIO_AUTH_TOKEN']}\n";
echo "From Number: {$twilioConfig['TWILIO_FROM_NUMBER']}\n";

echo "\n⚠️  Important:\n";
echo "1. Make sure your Twilio account has credits\n";
echo "2. Verify the phone number is correct\n";
echo "3. Test with your actual phone number\n";
