<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcomodacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acomodacoes', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->integer('tipo_id');
            $table->integer('numeros_leitos_urgencia')->nullable();
            $table->boolean('cobertura');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acomodacoes');
    }
}
