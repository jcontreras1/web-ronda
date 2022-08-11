<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeofenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geofence', function (Blueprint $table) {
            $table->id();
            $table->string('latitud');
            $table->string('longitud');
            $table->integer('radio');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('circuito_id');
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('circuito_id')->references('id')->on('circuito');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('geofence');
    }
}
