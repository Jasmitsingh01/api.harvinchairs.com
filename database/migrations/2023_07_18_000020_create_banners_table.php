<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannersTable extends Migration
{
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('type')->nullable();
            $table->string('title')->nullable();
            $table->longText('banner')->nullable();
            $table->integer('dis_index')->nullable();
            $table->boolean('active')->default(0)->nullable();
            $table->longText('link')->nullable();
            $table->string('link_open')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
