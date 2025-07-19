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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->unsigned();
            $table->string('reference_code')->nullable();
            $table->double('wholesale_price')->nullable();
            $table->string('impact_on_price')->nullable();
            $table->string('impact_on_price_of')->nullable();
            $table->string('impact_on_weight')->nullable();
            $table->string('impact_on_weight_of')->nullable();
            $table->double('minimum_quantity')->nullable();
            $table->date('availability_date')->nullable();
            $table->text('images')->nullable();
            $table->boolean('is_default')->default(0);
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
        Schema::dropIfExists('product_attributes');
    }
};
