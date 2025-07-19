<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menuitems', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->boolean('is_mega_menu');
            $table->string('name');
            $table->longText('banner_image');
            $table->string('slug');
            $table->string('type');
            $table->boolean('active');
            $table->string('color_code');
            $table->boolean('text_bold');
            $table->string('target');
            $table->bigInteger('menu_id');
            $table->bigInteger('category_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menuitems');
    }
};
