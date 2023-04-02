<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableContasReceber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas_receber', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('pessoa_id')->references('id')->on('pessoas');
            $table->integer('num_parcela');
            $table->date('data_vencimento');
            $table->decimal('valor_parcela', 8, 2);
            $table->string('tipo_parcelamento', 55)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->date('data_pago')->nullable();
            $table->decimal('valor_pago', 8, 2)->nullable();
            $table->string('forma_pagamento', 100)->nullable();
            $table->tinyInteger('processada')->default(0);
            $table->date('data_compensacao')->nullable()->default(null);
            $table->text('obs')->nullable()->default(null);
            $table->string('agencia', 55)->nullable();
            $table->string('conta', 55)->nullable();
            $table->string('banco', 55)->nullable();
            $table->date('data_emissao')->nullable();
            $table->integer('compensado')->nullable()->default(0);
            $table->integer('emitido_recebido')->nullable()->default(0);
            $table->string('bom_para', 55)->nullable();
            $table->string('titular', 150)->nullable();
            $table->string('numero_cheque', 150)->nullable();
            $table->tinyInteger('anual')->default(0);
            $table->string('descricao', 150)->nullable();
            $table->foreignId('instituicao_id')->default(1)->references('id')->on('instituicoes');
            $table->foreignId('conta_id')->nullable()->references('id')->on('contas');
            $table->foreignId('plano_conta_id')->nullable()->references('id')->on('planos_contas');
            $table->string('num_documento', 50)->nullable();
            $table->date('data_competencia')->nullable();
            $table->string('tipo', 50)->default('paciente')->comment('paciente, cliente');
            $table->tinyInteger('cancelar_parcela')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contas_receber');
    }
}
