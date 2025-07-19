<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementBannersTable extends Migration
{
    public function up()
    {
        Schema::create('advertisement_banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->boolean('active')->default(0)->nullable();
            $table->longText('link')->nullable();
            $table->string('link_open')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
