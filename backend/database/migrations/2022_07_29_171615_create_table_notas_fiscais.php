<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableNotasFiscais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas_fiscais', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->foreignId('contas_receber_id')->references('id')->on('contas_receber');
            $table->foreignId('pessoa_id')->nullable()->references('id')->on('pessoas');
            
            $table->string('status');

            $table->decimal('aliquota_iss', 3,2)->default(0)->comment('aliquota IIS percentual'); //Aliquota de ISS
            $table->decimal('valor_iis', 8,2)->default(0);
            $table->integer('iss_retido_fonte')->default(0)->comment('1 -> sim, 0 -> não');
            $table->string('cnae')->nullable();
            $table->decimal('valor_pis', 8,2)->default(0);
            $table->decimal('p_pis', 3,2)->default(0)->comment('pecentual pis'); // percentual de PIS
            $table->decimal('valor_confis', 8,2)->default(0);
            $table->decimal('p_cofins', 3,2)->default(0)->comment('pecentual COFINS'); // percentual COFINS
            $table->decimal('valor_inss', 8,2)->default(0);
            $table->decimal('p_inss', 3,2)->default(0)->comment('pecentual INSS'); // percentual INSS
            $table->decimal('valor_ir', 8,2)->default(0);
            $table->decimal('p_ir', 3,2)->default(0)->comment('pecentual imposto de renda'); // pecentual Imposto de Renda PJ
            
            $table->string('uf_prestacao_servico')->nullable();
            $table->string('municipio_prestacao_servico')->nullable();
            $table->string('descricao')->nullable();
            $table->string('cod_servico_municipal')->nullable();
            $table->string('descricao_servico_municipal')->nullable();
            $table->string('natureza_operacao')->nullable();
            $table->decimal('deducoes', 8,2)->nullable();
            $table->decimal('descontos', 8,2)->nullable();
            $table->decimal('valor_total', 8,2)->nullable();
            $table->text('observacoes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notas_fiscais');
    }
}
