<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSolicitacaoCompras extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacao_compras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->date('data_solicitacao')->nullable();
            $table->date('data_maxima')->nullable();
            $table->date('data_impressao')->nullable();

            $table->foreignId('instituicao_id')
                ->references('id')
                ->on('instituicoes')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->foreignId('setores_exames_id')
                ->references('id')
                ->on('setores_exame')
                ->onDelete('cascade')
                ->onUpdate('no action');
             
            $table->integer('cod_usuario')->nullable(); 
            $table->string('nome_solicitante','50')->nullable();     

            $table->foreignId('motivo_pedido_id')
                ->references('id')
                ->on('motivo_pedido')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->foreignId('comprador_id')
                ->references('id')
                ->on('comprador')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->foreignId('estoque_id')
                ->references('id')
                ->on('estoques')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->string('sol_agrup','50')->nullable();  
            $table->tinyInteger('servico_produto'); 
            $table->tinyInteger('urgente'); 
            $table->tinyInteger('solicitacao_opme');
            $table->string('atendimento','50')->nullable(); 
            $table->string('pre_int','50')->nullable();
            $table->string('av_cirurgia','50')->nullable();
            $table->date('data_maxima_apoio_cotacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitacao_compras', function (Blueprint $table) {
            //
        });
    }
}
