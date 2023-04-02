<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProntuarioConfiguracoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prontuario_configuracoes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->string('descricao');
            $table->tinyInteger('status')->default(1);
        });
        
        Schema::create('prontuario_configuracoes_itens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('prontuario_configuracao_id')->references('id')->on('prontuario_configuracoes')->index("prontuario_configuracao_id_index_itens");
            $table->string('tipo_item');
            $table->json('item');
            $table->tinyInteger('status')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prontuario_configuracoes');
        Schema::dropIfExists('prontuario_configuracoes_itens');
    }
}
