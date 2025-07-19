<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            // $table->dropColumn('type');
            $table->dropColumn('amount');
        });
        Schema::table('coupons', function (Blueprint $table) {
            $table->string('customer_id')->nullable()->after('code');
            $table->enum('discount_type',['percentage','amount'])->after('image');
            $table->double('discount')->after('image');
            $table->integer('max_redemption_per_user')->comment('maximum redemption for single user')->after('image');
            $table->integer('min_amount')->nullable();
            $table->integer('min_qty')->nullable();
            $table->integer('max_usage')->nullable();
            $table->integer('usage_count_per_user')->nullable();
            $table->string('country')->nullable();
            $table->enum('is_used',['1','0'])->default('0');
            $table->string('category_id')->nullable();
            $table->string('product_id')->nullable();
            $table->enum('free_shipping',['1','0'])->default('0');
            $table->string('attributes')->nullable()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
