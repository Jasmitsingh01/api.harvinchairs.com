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
        Schema::table('order_product', function (Blueprint $table) {
            // Replace 'foreign_key_column' with the actual name of the foreign key column
            $table->dropForeign(['variation_option_id']);
        });

        // Step 2: Drop the column
        Schema::table('order_product', function (Blueprint $table) {
            // Replace 'column_to_drop' with the actual name of the column you want to drop
            $table->dropColumn('variation_option_id');
            $table->bigInteger('product_attribute_id')->unsigned()->nullable()->after('product_id');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
