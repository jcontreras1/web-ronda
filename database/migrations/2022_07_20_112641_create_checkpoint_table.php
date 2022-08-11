<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckpointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checkpoint', function (Blueprint $table) {
            $table->id();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
            $table->text('novedad')->nullable();
            $table->unsignedBigInteger('ronda_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('ronda_id')->references('id')->on('ronda');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('checkpoint');
    }
}
