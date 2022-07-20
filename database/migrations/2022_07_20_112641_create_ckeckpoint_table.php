<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCkeckpointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ckeckpoint', function (Blueprint $table) {
            $table->id();
            $table->float('latitud')->nullable();
            $table->float('longitud')->nullable();
            $table->unsignedBigInteger('ronda_id');
            $table->timestamps();

            $table->foreign('ronda_id')->references('id')->on('ronda');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign(['ronda_id']);
        Schema::dropIfExists('ckeckpoint');
    }
}
