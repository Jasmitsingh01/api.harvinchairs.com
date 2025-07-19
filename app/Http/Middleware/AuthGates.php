<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        
        if (! $user) {
            return $next($request);
        }

        try {
            // Check if admin_permissions table exists
            if (!\Schema::hasTable('admin_permissions')) {
                // If permissions table doesn't exist, skip permission setup
                return $next($request);
            }

            $roles = Role::with('adminPermissions')->get();
            $permissionsArray = [];

            foreach ($roles as $role) {
                if ($role->adminPermissions) {
                    foreach ($role->adminPermissions as $permissions) {
                        $permissionsArray[$permissions->title][] = $role->id;
                    }
                }
            }

            foreach ($permissionsArray as $title => $roles) {
                Gate::define($title, function ($user) use ($roles) {
                    // Check if user has roles before accessing them
                    if (!$user || !$user->roles) {
                        return false;
                    }
                    return count(array_intersect($user->roles->pluck('id')->toArray(), $roles)) > 0;
                });
            }
        } catch (\Exception $e) {
            // If there's any error, continue without permissions
            // Log the error for debugging
            \Log::error('AuthGates middleware error: ' . $e->getMessage());
        }

        return $next($request);
    }
}
