<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
        Schema::connection('mysql')->create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
        Schema::connection('mysql')->create('items_stores', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
            $table->timestamps();
        });
        Schema::connection('mysql2')->create('items_stores', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores');
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
        Schema::connection('mysql2')->dropIfExists('stores');
        Schema::connection('mysql')->dropIfExists('stores');
    }
}
