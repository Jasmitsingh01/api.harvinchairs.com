<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailLogsTable extends Migration
{
    public function up()
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('recipient');
            $table->string('template_name');
            $table->string('subject')->nullable();
            $table->string('language')->nullable();
            $table->string('status')->nullable();
            $table->string('orderid')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
