<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Database\Models\Attribute;
use App\Database\Models\AttributeValue;
use App\Database\Models\Product;
use App\Database\Models\User;
use App\Database\Models\Category;
use App\Database\Models\Type;
use App\Database\Models\Order;
use App\Database\Models\OrderStatus;
use App\Database\Models\Coupon;
use Spatie\Permission\Models\Permission;
use App\Enums\Permission as UserPermission;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            // UsersTableSeeder::class,
            RoleUserTableSeeder::class,
        ]);
    }
}
