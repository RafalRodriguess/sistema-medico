<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCodigosFiscais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configuracoes_fiscais', function (Blueprint $table) {
            $table->id();
            $table->timestamps();            
            $table->softDeletes();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->string('descricao');
            $table->decimal('aliquota_iss', 8,2)->default(0)->comment('aliquota IIS percentual'); //Aliquota de ISS
            $table->integer('iss_retido_fonte')->default(0)->comment('1 -> sim, 0 -> não');
            $table->string('cnae')->nullable();
            $table->string('cod_servico_municipal')->nullable();
            $table->decimal('p_pis', 3,2)->default(0)->comment('pecentual pis'); // percentual de PIS
            $table->decimal('p_cofins', 3,2)->default(0)->comment('pecentual COFINS'); // percentual COFINS
            $table->decimal('p_inss', 3,2)->default(0)->comment('pecentual INSS'); // percentual INSS
            $table->decimal('p_ir', 3,2)->default(0)->comment('pecentual imposto de renda'); // pecentual Imposto de Renda PJ
            $table->string('regime'); // regime de contribuição
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('configuracoes_fiscais');
    }
}
