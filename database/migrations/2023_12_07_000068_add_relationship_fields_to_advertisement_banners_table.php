<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToAdvertisementBannersTable extends Migration
{
    public function up()
    {
        Schema::table('advertisement_banners', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id', 'category_fk_9283797')->references('id')->on('categories');
        });
    }
}
