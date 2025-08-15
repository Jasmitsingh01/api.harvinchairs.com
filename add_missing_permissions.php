<?php

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Permission;
use Illuminate\Support\Facades\DB;

try {
    echo "Adding missing advertisement banner permissions...\n";
    
    // Check if advertisement banner permissions exist
    $existingPermissions = Permission::where('title', 'like', '%advertisement%')->get();
    echo "Existing advertisement permissions: " . $existingPermissions->count() . "\n";
    
    // Define the missing permissions
    $missingPermissions = [
        [
            'id' => 218,
            'title' => 'advertisement_banner_create',
        ],
        [
            'id' => 219,
            'title' => 'advertisement_banner_edit',
        ],
        [
            'id' => 220,
            'title' => 'advertisement_banner_show',
        ],
        [
            'id' => 221,
            'title' => 'advertisement_banner_delete',
        ],
        [
            'id' => 222,
            'title' => 'advertisement_banner_access',
        ],
    ];
    
    foreach ($missingPermissions as $permissionData) {
        $existing = Permission::where('title', $permissionData['title'])->first();
        
        if (!$existing) {
            // Create the permission
            Permission::create([
                'id' => $permissionData['id'],
                'title' => $permissionData['title'],
            ]);
            echo "✅ Created permission: {$permissionData['title']}\n";
        } else {
            echo "✅ Permission already exists: {$permissionData['title']}\n";
        }
    }
    
    // Now assign these permissions to the admin role
    $adminRole = \App\Models\Role::find(1);
    if ($adminRole) {
        echo "\nAssigning permissions to admin role...\n";
        
        foreach ($missingPermissions as $permissionData) {
            $permission = Permission::where('title', $permissionData['title'])->first();
            if ($permission) {
                // Check if already assigned
                $alreadyAssigned = DB::table('admin_permission_role')
                    ->where('role_id', $adminRole->id)
                    ->where('permission_id', $permission->id)
                    ->exists();
                
                if (!$alreadyAssigned) {
                    DB::table('admin_permission_role')->insert([
                        'role_id' => $adminRole->id,
                        'permission_id' => $permission->id
                    ]);
                    echo "✅ Assigned {$permissionData['title']} to admin role\n";
                } else {
                    echo "✅ {$permissionData['title']} already assigned to admin role\n";
                }
            }
        }
    }
    
    echo "\n✅ Advertisement banner permissions setup complete!\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
