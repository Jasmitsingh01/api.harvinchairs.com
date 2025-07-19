<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialOffersTable extends Migration
{
    public function up()
    {
        Schema::create('special_offers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('offer_type')->nullable();
            $table->string('discount_type')->nullable();
            $table->float('discount', 15, 2)->nullable();
            $table->float('order_total_condition', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
