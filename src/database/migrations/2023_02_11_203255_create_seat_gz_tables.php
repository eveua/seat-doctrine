<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeatGzTables extends Migration
{
    public function up()
    {
        Schema::create('seat_gz_fitting', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('ship');
            $table->integer('shipID');
            $table->json('fit');
            $table->text('description')->default('');
            $table->timestamps();
        });

        Schema::create('seat_gz_doctrine', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->default('');
            $table->timestamps();
        });

        Schema::create('seat_gz_doctrine_fitting', function (Blueprint $table) {
            $table->unsignedInteger('doctrine_id');
            $table->unsignedInteger('fitting_id');
            $table->foreign('doctrine_id')
                ->references('id')
                ->on('seat_gz_doctrine')
                ->onDelete('cascade');
            $table->foreign('fitting_id')
                ->references('id')
                ->on('seat_gz_fitting')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seat_gz_doctrine_fitting');
        Schema::dropIfExists('seat_gz_fitting');
        Schema::dropIfExists('seat_gz_doctrine');
    }
}
