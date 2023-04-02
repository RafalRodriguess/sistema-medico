<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaineisTotemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paineis_totem', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->unsignedBigInteger('origens_id');
            $table->foreignId('instituicoes_id')->references('id')->on('instituicoes')->onDelete('cascade');
            $table->timestamps();

            $table->foreign('origens_id')->references('id')->on('origens')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paineis_totem');
    }
}
