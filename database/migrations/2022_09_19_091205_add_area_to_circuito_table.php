<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAreaToCircuitoTable extends Migration
{

    public function up()
    {
        Schema::table('circuito', function (Blueprint $table) {
            $table->unsignedBigInteger('area_id')->nullable();
        });
    }

    public function down()
    {
        Schema::table('circuito', function (Blueprint $table) {
            $table->dropForeign(['area_id']);
            $table->dropColumn('area_id');
        });
    }
}
