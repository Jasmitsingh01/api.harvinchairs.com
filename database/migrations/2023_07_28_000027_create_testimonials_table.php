<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestimonialsTable extends Migration
{
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('author_name')->nullable();
            $table->string('author_info')->nullable();
            $table->string('author_url')->nullable();
            $table->string('author_email')->nullable();
            $table->integer('rating')->nullable();
            $table->string('content')->nullable();
            $table->boolean('is_featured')->default(0)->nullable();
            $table->boolean('active')->default(0)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
