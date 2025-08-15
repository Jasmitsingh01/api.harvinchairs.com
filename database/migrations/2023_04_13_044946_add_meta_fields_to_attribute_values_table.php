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
        Schema::table('attribute_values', function (Blueprint $table) {
            $table->text('description')->after('value')->nullable();
            $table->string('cover_image')->after('value')->nullable();
            $table->text('meta_keywords')->after('value')->nullable();
            $table->text('meta_description')->after('value')->nullable();
            $table->string('meta_title')->after('value')->nullable();
            $table->integer('position')->after('language');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attribute_values', function (Blueprint $table) {
            //
        });
    }
};
