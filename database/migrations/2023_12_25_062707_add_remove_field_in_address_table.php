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
        Schema::table('address', function (Blueprint $table) {
            $table->string('first_name');
            $table->string('address_name');
            $table->string('email');
            $table->string('mobile_number');
            $table->string('alternate_mobile_number')->nullable();
            $table->string('postal_code');
            $table->string('society');
            $table->string('area');
            $table->string('landmark')->nullable();
            $table->string('city');
            $table->string('state');
            $table->boolean('is_service_lift')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('address', function (Blueprint $table) {
            $table->dropColumn('first_name');
            $table->dropColumn('address_name');
            $table->dropColumn('email');
            $table->dropColumn('mobile_number');
            $table->dropColumn('alternate_mobile_number');
            $table->dropColumn('postal_code');
            $table->dropColumn('society');
            $table->dropColumn('area');
            $table->dropColumn('landmark');
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('is_service_lift');
        });
    }
};
