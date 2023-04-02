<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class CreateTableInternacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->references('id')->on('pessoas');
            $table->datetime('previsao');
            $table->foreignId('origem_id')->references('id')->on('origens');
            $table->foreignId('medico_id')->references('id')->on('instituicoes_prestadores');
            $table->foreignId('especialidade_id')->references('id')->on('especialidades');
            $table->foreignId('cid_id')->references('id')->on('cids');
            $table->foreignId('acomodacao_id')->references('id')->on('acomodacoes');
            $table->foreignId('unidade_id')->references('id')->on('unidades_internacoes');
            $table->integer('acompanhante')->comment('1 -> Sim, 0 -> Não');
            $table->integer('tipo_internacao')->comment('1 -> Clínico, 2 -> Cirúrgico, 3 -> Materno-Infantil, 4 -> Neonatalogia, 5-> Obstetrícia, 6-> Pediatria, 7 -> Psiquiatria, 8 -> Outros');
            $table->foreignId('leito_id')->references('id')->on('unidades_leitos');
            $table->integer('reserva_leito')->comment('1 -> Sim, 0 -> Não');
            $table->integer('pre_internacao')->comment('1 -> Sim, 0 -> Não');
            $table->text('observacao')->nullable();
            $table->foreignId('instituicao_id')->references('id')->on("instituicoes");
            $table->integer('possui_responsavel')->default(0)->comment('1 -> Sim, 0 -> Não');
            $table->string('parentesco_responsavel')->nullable();
            $table->string('nome_responsavel')->nullable();
            $table->string('estado_civil_responsavel')->nullable();
            $table->string('profissao_responsavel')->nullable();
            $table->string('nacionalidade_responsavel')->nullable();
            $table->string('telefone1_responsavel')->nullable();
            $table->string('telefone2_responsavel')->nullable();
            $table->string('identidade_responsavel')->nullable();
            $table->string('cpf_responsavel')->nullable();
            $table->string('contato_responsavel')->nullable();
            $table->string('cep_responsavel')->nullable();
            $table->string('endereco_responsavel')->nullable();
            $table->string('numero_responsavel')->nullable();
            $table->string('complemento_responsavel')->nullable();
            $table->string('bairro_responsavel')->nullable();
            $table->string('cidade_responsavel')->nullable();
            $table->string('uf_responsavel')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internacao');
    }
}
