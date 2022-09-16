<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagenCheckpointTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagen_checkpoint', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->unsignedBigInteger('checkpoint_id');
            $table->timestamps();
            $table->foreign('checkpoint_id')->references('id')->on('checkpoint');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagen_checkpoint');
    }
}
