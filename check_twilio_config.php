<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔍 Checking Twilio Configuration...\n";
echo "==================================\n\n";

// Check environment variables directly
$accountSid = $_ENV['TWILIO_ACCOUNT_SID'] ?? null;
$authToken = $_ENV['TWILIO_AUTH_TOKEN'] ?? null;
$fromNumber = $_ENV['TWILIO_FROM_NUMBER'] ?? null;

echo "Environment Variables:\n";
echo "TWILIO_ACCOUNT_SID: " . ($accountSid ?: '❌ Not found') . "\n";
echo "TWILIO_AUTH_TOKEN: " . ($authToken ? substr($authToken, 0, 10) . '...' : '❌ Not found') . "\n";
echo "TWILIO_FROM_NUMBER: " . ($fromNumber ?: '❌ Not found') . "\n\n";

// Check config values
echo "Config Values:\n";
echo "Account SID: " . (config('services.twilio.account_sid') ?: '❌ Not found') . "\n";
echo "Auth Token: " . (config('services.twilio.auth_token') ? substr(config('services.twilio.auth_token'), 0, 10) . '...' : '❌ Not found') . "\n";
echo "From Number: " . (config('services.twilio.from_number') ?: '❌ Not found') . "\n\n";

// Check if .env file exists and read it
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    echo "📄 .env file exists\n";
    $envContent = file_get_contents($envFile);
    
    if (strpos($envContent, 'TWILIO_ACCOUNT_SID') !== false) {
        echo "✅ TWILIO_ACCOUNT_SID found in .env\n";
    } else {
        echo "❌ TWILIO_ACCOUNT_SID not found in .env\n";
    }
    
    if (strpos($envContent, 'TWILIO_AUTH_TOKEN') !== false) {
        echo "✅ TWILIO_AUTH_TOKEN found in .env\n";
    } else {
        echo "❌ TWILIO_AUTH_TOKEN not found in .env\n";
    }
    
    if (strpos($envContent, 'TWILIO_FROM_NUMBER') !== false) {
        echo "✅ TWILIO_FROM_NUMBER found in .env\n";
    } else {
        echo "❌ TWILIO_FROM_NUMBER not found in .env\n";
    }
} else {
    echo "❌ .env file not found!\n";
}

echo "\n💡 If configuration is not found, try:\n";
echo "1. Restart your terminal/command prompt\n";
echo "2. Run: php artisan config:clear\n";
echo "3. Run: php artisan cache:clear\n";
