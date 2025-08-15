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
        Schema::table('orders', function (Blueprint $table) {
            $table->longText('billing_address_detail')->nullable();
            $table->longText('shipping_address_detail')->nullable();
            $table->longText('address_firstname')->nullable();
            $table->longText('address_mobile_no')->nullable();
            $table->longText('address_alternate_mobile_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('billing_address_detail');
            $table->dropColumn('shipping_address_detail');
            $table->dropColumn('address_firstname')->nullable();
            $table->dropColumn('address_mobile_no')->nullable();
            $table->dropColumn('address_alternate_mobile_no')->nullable();
        });
    }
};
