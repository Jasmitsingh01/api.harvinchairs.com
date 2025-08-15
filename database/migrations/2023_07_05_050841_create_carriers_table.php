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
        Schema::create('carriers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reference_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('url')->nullable();
            $table->boolean('active')->default(true);
            $table->boolean('shipping_handling')->default(false);
            $table->boolean('range_behavior')->default(false);
            $table->boolean('is_module')->default(false);
            $table->boolean('is_free')->default(false);
            $table->boolean('shipping_external')->default(false);
            $table->boolean('need_range')->default(false);
            $table->string('external_module_name')->nullable();
            $table->integer('shipping_method')->nullable();
            $table->integer('position')->nullable();
            $table->double('max_width')->nullable();
            $table->double('max_height')->nullable();
            $table->double('max_depth')->nullable();
            $table->double('max_weight')->nullable();
            $table->integer('grade')->nullable();
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carriers');
    }
};
