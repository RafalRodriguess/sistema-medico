<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilasTotemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filas_totem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filas_triagem_id');
            $table->unsignedBigInteger('totens_id');

            $table->foreign('filas_triagem_id')->references('id')->on('filas_triagem')->onDelete('cascade');
            $table->foreign('totens_id')->references('id')->on('totens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filas_totem');
    }
}
