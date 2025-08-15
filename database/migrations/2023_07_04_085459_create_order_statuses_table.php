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
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('template')->nullable();
            $table->string('module_name')->nullable();
            $table->boolean('invoice')->default(false);
            $table->boolean('send_email')->default(false);
            $table->boolean('unremovable')->default(false);
            $table->boolean('hidden')->default(false);
            $table->boolean('logable')->default(false);
            $table->boolean('delivery')->default(false);
            $table->boolean('shipped')->default(false);
            $table->boolean('paid')->default(false);
            $table->boolean('pdf_invoice')->default(false);
            $table->boolean('pdf_delivery')->default(false);
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
        Schema::dropIfExists('order_statuses');
    }
};
