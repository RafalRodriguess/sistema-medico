<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVinculoTuss extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vinculo_tuss', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->bigInteger('cod_termo')->nullable();
            $table->text('termo')->nullable();
            $table->text('descricao_detalhada')->nullable();
            $table->date('data_vigencia')->nullable();
            $table->date('data_vigencia_fim')->nullable();
            $table->date('data_implantacao_fim')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vinculo_tuss');
    }
}
