<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVinculoBrasindice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vinculo_brasindice', function (Blueprint $table) {
            $table->id();
            $table->foreignId('importacao_id')->references('id')->on('vinculo_brasindice_importacoes');
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->foreignId('tipo_id')->references('id')->on('vinculo_brasindice_tipos');

            $table->integer('laboratorio_cod');
            $table->string('laboratorio', 40);

            $table->integer('medicamento_cod', false, true);
            $table->string('medicamento', 80);

            $table->string('apresentacao_cod', 10);
            $table->string('apresentacao', 150);

            $table->decimal('preco_medicamento', 15, 2)->default(0.00);
            $table->unsignedMediumInteger('qtd_fracionamento');
            $table->string('tipo_preco', 4);
            $table->decimal('valor_fracionado', 15, 2)->default(0.00);
            $table->unsignedSmallInteger('edicao');
            $table->decimal('ipi_medicamento', 5, 2)->default(0.00);
            $table->string('flag_pis_confins', 1);

            $table->unsignedBigInteger('ean')->nullable()->default(null);
            $table->integer('tiss', false, true);
            $table->string('flag_generico', 1)->nullable();
            $table->integer('tuss', false, true)->nullable()->default(null);

            $table->timestamps();
            $table->softDeletes();

            $table->index('medicamento_cod', 'vinculo_brasindice_medicamento_cod_index');
            $table->index('medicamento', 'vinculo_brasindice_medicamento_index');

            $table->index('laboratorio_cod', 'vinculo_brasindice_laboratorio_cod_index');

            $table->index('tipo_preco', 'vinculo_brasindice_tipo_preco_index');

            $table->index('tiss', 'vinculo_brasindice_tiss_index');
            $table->index('ean', 'vinculo_brasindice_ean_index');
            $table->index('tuss', 'vinculo_brasindice_tuss_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vinculo_brasindice');
    }
}
