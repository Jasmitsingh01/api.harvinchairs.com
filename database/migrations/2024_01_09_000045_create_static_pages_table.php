<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaticPagesTable extends Migration
{
    public function up()
    {
        Schema::create('static_pages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('slug');
            $table->string('meta_title')->nullable();
            $table->longText('meta_description')->nullable();
            $table->longText('content');
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
