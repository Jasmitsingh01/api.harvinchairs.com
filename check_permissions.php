<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Permission;

try {
    echo "Checking admin permissions...\n";
    
    $totalPermissions = Permission::count();
    echo "Total permissions: {$totalPermissions}\n\n";
    
    // Check for advertisement banner permissions
    $advertisementPermissions = Permission::where('title', 'like', '%advertisement%')->get();
    echo "Advertisement banner permissions:\n";
    foreach ($advertisementPermissions as $permission) {
        echo "- {$permission->title} (ID: {$permission->id})\n";
    }
    
    echo "\nAll permissions:\n";
    $allPermissions = Permission::all();
    foreach ($allPermissions as $permission) {
        echo "- {$permission->title} (ID: {$permission->id})\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
