<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGruposFaturamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos_faturamento', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->string('descricao');
            $table->string('tipo');
            $table->tinyInteger('ativo')->nullable()->default(1);
            $table->tinyInteger('val_grupo_faturamento')->nullable()->default(0);
            $table->tinyInteger('rateio_nf')->nullable()->default(0);
            $table->tinyInteger('incide_iss')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupos_faturamento');
    }
}
