<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('payments', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
            $table->string('type');
            $table->dateTime('date');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
            $table->timestamps();
        });
        Schema::connection('mysql')->create('payments', function (Blueprint $table) {
            $table->id();
            $table->float('amount');
            $table->string('type');
            $table->dateTime('date');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
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
        Schema::connection('mysql2')->dropIfExists('payments');
        Schema::connection('mysql')->dropIfExists('payments');
    }
}
