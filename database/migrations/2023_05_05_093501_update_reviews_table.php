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
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('title')->after('variation_option_id')->nullable();
            $table->integer('old_id')->unsigned()->default(0)->after('id');
            $table->string('customer_name')->after('rating')->nullable();
            $table->boolean('is_active')->after('rating')->default(true);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
