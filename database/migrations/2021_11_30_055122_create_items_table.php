<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('price');
            $table->unsignedInteger('quantity');
            $table->string('description');
            $table->string('image');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id')->references('id')->on('users');


            $table->timestamps();
        });
        Schema::connection('mysql2')->create('item_owner', function (Blueprint $table) {

            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->float('qty');
            $table->timestamps();
        });
        Schema::connection('mysql')->create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('price');
            $table->unsignedInteger('quantity');
            $table->string('description');
            $table->string('image');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->unsignedBigInteger('creator_id')->nullable();
            $table->foreign('creator_id')->references('id')->on('users');
            
            $table->timestamps();
        });
        Schema::connection('mysql')->create('item_owner', function (Blueprint $table) {

            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreign('item_id')->references('id')->on('items');
            $table->unsignedBigInteger('owner_id')->nullable();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->float('qty');
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
        Schema::connection('mysql')->dropIfExists('items');
        Schema::connection('mysql2')->dropIfExists('items');
    }
}
