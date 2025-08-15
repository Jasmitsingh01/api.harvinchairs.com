<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

try {
    echo "Assigning all permissions to admin user...\n";
    
    // Find the admin user in the admin users table
    $user = User::where('email', 'admin2@harvinchairs.com')->first();
    
    if (!$user) {
        echo "❌ Admin user 'admin2@harvinchairs.com' not found in admin users table!\n";
        exit(1);
    }
    
    echo "Found user: {$user->email}\n";
    echo "User ID: {$user->id}\n";
    
    // Get all permissions from the admin_permissions table
    $allPermissions = Permission::all();
    
    if ($allPermissions->isEmpty()) {
        echo "❌ No permissions found in database!\n";
        echo "You may need to run the permissions seeder first.\n";
        exit(1);
    }
    
    echo "Total permissions available: " . $allPermissions->count() . "\n";
    
    // Get the admin role (usually ID 1)
    $adminRole = Role::find(1);
    
    if (!$adminRole) {
        echo "❌ Admin role not found!\n";
        exit(1);
    }
    
    echo "Admin role: {$adminRole->title}\n";
    
    // Check if user already has the admin role
    $userHasRole = DB::table('admin_role_user')
        ->where('user_id', $user->id)
        ->where('role_id', $adminRole->id)
        ->exists();
    
    if (!$userHasRole) {
        // Assign admin role to user
        DB::table('admin_role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $adminRole->id
        ]);
        echo "✅ Assigned admin role to user\n";
    } else {
        echo "✅ User already has admin role\n";
    }
    
    // Get current role permissions
    $currentRolePermissions = $adminRole->adminPermissions()->pluck('title');
    echo "Current role permissions: " . $currentRolePermissions->count() . "\n";
    
    // Get all permission titles
    $allPermissionTitles = $allPermissions->pluck('title');
    
    // Find permissions that need to be assigned to the role
    $permissionsToAssign = $allPermissionTitles->diff($currentRolePermissions);
    
    if ($permissionsToAssign->isEmpty()) {
        echo "✅ Admin role already has all permissions!\n";
    } else {
        echo "Permissions to assign to role: " . $permissionsToAssign->count() . "\n";
        
        // Get permission IDs to assign
        $permissionIds = Permission::whereIn('title', $permissionsToAssign)->pluck('id');
        
        // Assign permissions to the admin role
        foreach ($permissionIds as $permissionId) {
            DB::table('admin_permission_role')->insertOrIgnore([
                'role_id' => $adminRole->id,
                'permission_id' => $permissionId
            ]);
        }
        
        echo "✅ Successfully assigned " . $permissionIds->count() . " permissions to admin role!\n";
    }
    
    // Verify the assignment
    $updatedRolePermissions = $adminRole->fresh()->adminPermissions()->pluck('title');
    
    echo "\n✅ Successfully configured admin permissions!\n";
    echo "User: {$user->email}\n";
    echo "Role: {$adminRole->title}\n";
    echo "Total permissions now: " . $updatedRolePermissions->count() . "\n";
    
    // Show some key permissions that were assigned
    echo "\nKey permissions available:\n";
    $keyPermissions = [
        'profile_password_edit',
        'user_management_access',
        'permission_access',
        'role_access',
        'user_access',
        'category_access',
        'product_access',
        'order_access',
        'shop_access'
    ];
    
    foreach ($keyPermissions as $keyPermission) {
        if ($updatedRolePermissions->contains($keyPermission)) {
            echo "✅ {$keyPermission}\n";
        } else {
            echo "❌ {$keyPermission} (missing)\n";
        }
    }
    
    echo "\n🎉 Admin user now has full access to all features!\n";
    echo "You should now be able to access the password change page without 403 errors.\n";
    
} catch (\Exception $e) {
    echo "❌ Error assigning permissions: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
