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
        Schema::table('orders', function (Blueprint $table) {
            $table->float('average_cgst_rate')->default(0);
            $table->float('average_sgst_rate')->default(0);
            $table->float('total_cgst')->default(0);
            $table->float('total_sgst')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('average_cgst_rate');
            $table->dropColumn('average_sgst_rate');
            $table->dropColumn('total_cgst');
            $table->dropColumn('total_sgst');
        });
    }
};
