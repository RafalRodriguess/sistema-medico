<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriateTableContasPagar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas_pagar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instituicao_id')->nullable()->references('id')->on('instituicoes');
            $table->foreignId('pessoa_id')->nullable()->references('id')->on('pessoas');
            $table->integer('num_parcela')->nullable();
            $table->date('data_vencimento')->nullable();
            $table->decimal('valor_parcela', 8, 2)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->date('data_pago')->nullable();
            $table->decimal('valor_pago', 8, 2)->nullable();
            $table->string('forma_pagamento', 100)->nullable();
            $table->tinyInteger('processada')->default(0);
            $table->date('data_compensacao')->nullable()->default(null);
            $table->text('obs')->nullable()->default(null);
            $table->string('descricao', 150)->default();
            $table->foreignId('conta_id')->nullable()->references('id')->on('contas');
            $table->foreignId('plano_conta_id')->nullable()->references('id')->on('planos_contas');
            $table->foreignId('prestador_id')->nullable()->references('id')->on('prestadores');
            $table->string('numero_doc', 150)->nullable();
            $table->date('data_competencia')->nullable();
            $table->string('tipo', 25);

            $table->integer('cotacao')->default(0)->comment('0 - nao, 1 - sim');
            $table->string('status_cotacao')->nullable()->comment('solicitacao, confirmada, finalizada');

            $table->string('titular', 100)->nullable();
            $table->string('banco', 100)->nullable();
            $table->string('numero_cheque', 100)->nullable();

            $table->foreignId('conta_pai')->nullable()->default(null)->references('id')->on('contas_pagar');
            $table->decimal('total', 10,2);

            $table->string('agencia', 55)->nullable();
            $table->string('conta', 55)->nullable();
            $table->date('data_emissao')->nullable();
            $table->integer('compensado')->nullable()->default(0);
            $table->integer('emitido_recebido')->nullable()->default(0);
            $table->string('bom_para', 55)->nullable();

            $table->foreignId('forma_pagamento_id')->nullable()->references('id')->on('forma_pagamento');

            $table->date('data_emissao_nf')->nullable();
            $table->tinyInteger('nf_imposto')->default(0);

            $table->decimal('desc_juros_multa', 12,2)->nullable();

            $table->string('chave_pix')->nullable();

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
        Schema::dropIfExists('contas_pagar');
    }
}
