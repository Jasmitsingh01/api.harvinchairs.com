<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('products')) {
            return;
        }
        $connection = DB::connection();
        $hasIndex = $connection->getDoctrineSchemaManager()->listTableIndexes('products');
        if (!array_key_exists('old_id', $hasIndex)) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('old_id');
            });
        }
        if (!array_key_exists('name', $hasIndex)) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('name');
            });
        }
        if (!array_key_exists('type_id', $hasIndex)) {
            Schema::table('products', function (Blueprint $table) {
                $table->index('type_id');
            });
        } else {
            echo "Index already exists in products table.\n";
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        $connection = DB::connection();
        $hasIndex = $connection->getDoctrineSchemaManager()->listTableIndexes('products');

        if (array_key_exists('old_id', $hasIndex)) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('old_id');
            });
            echo "Index dropped successfully from old_id column in products table.\n";
        }
        if (array_key_exists('name', $hasIndex)) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('name');
            });
            echo "Index dropped successfully from old_id column in products table.\n";
        }
        if (array_key_exists('type_id', $hasIndex)) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('type_id');
            });
            echo "Index dropped successfully from old_id column in products table.\n";
        } else {
            echo "Index does not exist on old_id column in products table.\n";
        }
    }
};
