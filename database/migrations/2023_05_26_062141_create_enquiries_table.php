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
        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->text('subject');
            $table->text('message');
            $table->string('product_title')->nullable();
            $table->double('product_price')->nullable();
            $table->string('product_img')->nullable();
            $table->bigInteger('product_id')->unsigned()->nullable();
            $table->bigInteger('product_attributes_id')->unsigned()->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enquiries');
    }
};
