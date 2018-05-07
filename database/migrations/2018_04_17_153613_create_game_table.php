<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player1')->unsigned();
            $table->integer('player2')->unsigned();
            $table->foreign('player1')->references('id')->on('users');
            $table->foreign('player2')->references('id')->on('users');
            $table->integer('turn'); /*1 for niggas, 2 for whities, 3 for ended game*/
            $table->timestamps();
        });

        Schema::create('pieces', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game')->unsigned();
            $table->foreign('game')->references('id')->on('game');
            $table->integer('color'); /*1 for niggas, 2 for whities*/
            $table->integer('column');
            $table->integer('row');
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
        Schema::dropIfExists('game');
    }
}
