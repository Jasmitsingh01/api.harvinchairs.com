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
        if (!Schema::hasTable('attributes')) {
            return;
        }
        $connection = DB::connection();
        $hasIndex = $connection->getDoctrineSchemaManager()->listTableIndexes('attributes');
        if (!array_key_exists('old_id', $hasIndex)) {
            Schema::table('attributes', function (Blueprint $table) {
                $table->index('old_id');
            });
        }
        else {
            echo "Index already exists in attributes table.\n";
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('attributes')) {
            return;
        }

        $connection = DB::connection();
        $hasIndex = $connection->getDoctrineSchemaManager()->listTableIndexes('attributes');

        if (array_key_exists('old_id', $hasIndex)) {
            Schema::table('attributes', function (Blueprint $table) {
                $table->dropIndex('old_id');
            });
            echo "Index dropped successfully from old_id column in attributes table.\n";
        }
        else {
            echo "Index does not exist on old_id column in attributes table.\n";
        }
    }
};
