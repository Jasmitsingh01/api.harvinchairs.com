<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('custom_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email');
            $table->string('contact_no')->nullable();
            $table->string('stone_name')->nullable();
            $table->longText('description')->nullable();
            $table->string('entry_type')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
