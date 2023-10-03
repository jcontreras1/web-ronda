<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tarea', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('creador_id');
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->unsignedBigInteger('prioridad_id')->nullable();
            $table->timestamp('vencimiento')->nullable();
            $table->boolean('renovable')->default(false);
            $table->boolean('finalizada')->default(false);
            $table->timestamps();

            $table->foreign('prioridad_id')->references('id')->on('tarea_prioridad');
            $table->foreign('creador_id')->references('id')->on('users');
            $table->foreign('responsable_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tarea');
    }
}
