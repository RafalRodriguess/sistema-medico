<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCentrosDeCustos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centros_de_custos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->bigInteger('pai_id')->nullable();
            $table->integer('grupo_id');
            $table->string('descricao');
            $table->boolean('lancamento');
            $table->boolean('ativo');
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
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
        Schema::dropIfExists('centros_de_custos');
    }
}
