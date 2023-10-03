<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareaSubtareaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarea_subtarea', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->unsignedBigInteger('tarea_id');
            $table->unsignedBigInteger('creador_id');
            $table->timestamp('vencimiento')->nullable();
            $table->boolean('finalizada')->default(false);
            $table->timestamps();

            $table->foreign('tarea_id')->references('id')->on('tarea');
            $table->foreign('creador_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarea_subtarea');
    }
}
