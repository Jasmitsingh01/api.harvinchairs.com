<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOurStoresTable extends Migration
{
    public function up()
    {
        Schema::create('our_stores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('short_line')->nullable();
            $table->string('address')->nullable();
            $table->string('opening_hours')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('city');
            $table->string('pincode');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
