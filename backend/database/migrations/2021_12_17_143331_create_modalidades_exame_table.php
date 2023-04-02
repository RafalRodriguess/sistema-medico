<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModalidadesExameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modalidades_exame', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicao_id');
            $table->string('sigla');
            $table->string('descricao')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('instituicao_id')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modalidade_exame');
    }
}
