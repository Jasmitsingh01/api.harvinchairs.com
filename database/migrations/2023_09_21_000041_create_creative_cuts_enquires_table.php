<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreativeCutsEnquiresTable extends Migration
{
    public function up()
    {
        Schema::create('creative_cuts_enquires', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('description')->nullable();
            $table->string('upload_file')->nullable();
            $table->string('product_name')->nullable();
            $table->boolean('is_active')->default(0)->nullable();
            $table->boolean('notification')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
