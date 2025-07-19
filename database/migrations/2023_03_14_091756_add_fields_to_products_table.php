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
        Schema::table('products', function (Blueprint $table) {
            $table->text('video_description')->after('external_product_button_text')->nullable();
            $table->string('video_heading')->after('external_product_button_text')->nullable();
            $table->string('cover_image')->after('external_product_button_text')->nullable();
            $table->string('video_link')->after('external_product_button_text')->nullable();
            $table->time('to_time')->after('external_product_button_text')->nullable();
            $table->time('from_time')->after('external_product_button_text')->nullable();
            $table->date('to_date')->after('external_product_button_text')->nullable();
            $table->date('from_date')->after('external_product_button_text')->nullable();
            $table->string('friendly_url')->after('external_product_button_text')->nullable();
            $table->text('meta_description')->after('external_product_button_text')->nullable();
            $table->string('meta_title')->after('external_product_button_text')->nullable();
            $table->string('unit_price_per')->after('external_product_button_text')->nullable();
            $table->string('unit_price')->after('external_product_button_text')->nullable();
            $table->string('retail_price')->after('external_product_button_text')->nullable();
            $table->string('redirect_when_disabled')->after('external_product_button_text')->nullable();
            $table->string('options')->after('external_product_button_text')->nullable();
            $table->string('conditions')->after('external_product_button_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
