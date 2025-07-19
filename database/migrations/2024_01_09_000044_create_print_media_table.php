<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintMediaTable extends Migration
{
    public function up()
    {
        Schema::create('print_media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->unique();
            $table->date('publish_date');
            $table->string('is_print_media')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
