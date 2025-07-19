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
        Schema::table('countries', function (Blueprint $table) {
            $table->boolean('active')->after('zone_id')->default(true);
            $table->boolean('contains_states')->after('zone_id')->default(true);
            $table->boolean('need_identification_number')->after('zone_id')->default(true);
            $table->boolean('need_zip_code')->after('zone_id')->default(true);
            $table->string('zip_code_format')->after('zone_id')->nullable();
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
        Schema::table('countries', function (Blueprint $table) {
            //
        });
    }
};
