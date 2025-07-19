<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('customer_id');
            $table->dropColumn('discount_for');
            $table->dropColumn('min_qty');
            $table->dropColumn('usage_count_per_user');
            $table->dropColumn('country');
            // $table->dropColumn('category_id');
            // $table->dropColumn('product_id');
            $table->dropColumn('attributes');
            $table->dropColumn('country_id');
            $table->integer('free_shipping_min_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            //
        });
    }
};
