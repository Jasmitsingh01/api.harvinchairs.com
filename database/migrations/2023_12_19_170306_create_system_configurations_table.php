<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSystemConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('varname');
            $table->mediumText('description');
            $table->mediumText('value');
            $table->integer('option_order')->default(0);
            $table->enum('var_type',['text','radio','textarea','list','file'])->default('text');
            $table->mediumText('var_opt_val');
            $table->mediumText('var_opt_display');
            $table->timestamps();
            $table->integer('created_by')->default(0);
            $table->integer('updated_by')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_configurations');
    }
}
