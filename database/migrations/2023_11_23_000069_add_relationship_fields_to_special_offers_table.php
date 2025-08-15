<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToSpecialOffersTable extends Migration
{
    public function up()
    {
        Schema::table('special_offers', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id', 'product_fk_9237207')->references('id')->on('products');
        });
    }
}
