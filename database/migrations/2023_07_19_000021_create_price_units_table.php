<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('price_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->boolean('is_active')->default(0)->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
