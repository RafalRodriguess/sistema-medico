<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePlanoContas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planos_contas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instituicao_id')->references('id')->on("instituicoes");
            $table->string("descricao");
            $table->string("codigo");
            $table->foreignId("plano_conta_id")->nullable()->references('id')->on("planos_contas");
            $table->integer("padrao")->comment("1 - Credito, 0 - Debitos");
            $table->integer("rateio_auto")->comment("1 - sim, 0 - não");
            $table->foreignId("centro_custo_id")->nullable()->references('id')->on("centros_de_custos");
            $table->integer("rateio")->nullable()->comment("1 - valor, 0 - percentual");
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
        Schema::dropIfExists('planos_contas');
    }
}
