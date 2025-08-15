<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToBannersTable extends Migration
{
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
        });
    }
}
