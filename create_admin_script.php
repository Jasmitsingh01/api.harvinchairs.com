<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Database\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use App\Enums\Permission as UserPermission;
use Illuminate\Support\Facades\Validator;

try {
    echo "Updating admin user with all permissions...\n";
    
    // Clear permission cache
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    // First, let's check what permissions exist and update them to use api guard
    echo "Checking existing permissions...\n";
    
    // Get all existing permissions
    $existingPermissions = Permission::all();
    echo "Found " . $existingPermissions->count() . " existing permissions.\n";
    
    // Update all existing permissions to use api guard
    foreach ($existingPermissions as $permission) {
        if ($permission->guard_name !== 'api') {
            echo "Updating permission '{$permission->name}' from '{$permission->guard_name}' to 'api' guard.\n";
            $permission->update(['guard_name' => 'api']);
        }
    }
    
    // Create permissions with the correct guard (api)
    Permission::firstOrCreate(['name' => UserPermission::SUPER_ADMIN, 'guard_name' => 'api']);
    Permission::firstOrCreate(['name' => UserPermission::CUSTOMER, 'guard_name' => 'api']);
    Permission::firstOrCreate(['name' => UserPermission::STORE_OWNER, 'guard_name' => 'api']);
    Permission::firstOrCreate(['name' => UserPermission::STAFF, 'guard_name' => 'api']);

    // Find the existing admin user
    $email = "admin2@harvinchairs.com";
    
    echo "Looking for existing user: $email\n";
    
    $user = User::where('email', $email)->first();
    
    if (!$user) {
        echo "❌ User '$email' not found in database!\n";
        echo "Please create the user first or check the email address.\n";
        exit(1);
    }
    
    echo "✅ Found user: {$user->first_name} ({$user->email})\n";
    echo "Current permissions: " . $user->getPermissionNames()->count() . "\n\n";

    // Get all permissions from the database (using api guard)
    $allPermissions = Permission::where('guard_name', 'api')->get();
    
    if ($allPermissions->isEmpty()) {
        echo "❌ No permissions found in database with 'api' guard!\n";
        exit(1);
    }
    
    echo "Found " . $allPermissions->count() . " permissions in database (api guard).\n";
    
    // Remove all existing permissions and assign ALL permissions to the user
    $user->syncPermissions($allPermissions);

    echo "✅ Admin user updated successfully!\n";
    echo "User details:\n";
    echo "- Email: {$user->email}\n";
    echo "- Name: {$user->first_name}\n";
    echo "- Total permissions assigned: " . $allPermissions->count() . "\n";
    echo "- All permissions have been assigned to this user.\n";
    
    // Show current permissions count
    echo "Current user permissions count: " . $user->getPermissionNames()->count() . "\n";

} catch (\Exception $e) {
    echo "❌ Error updating admin user: " . $e->getMessage() . "\n";
    exit(1);
}
