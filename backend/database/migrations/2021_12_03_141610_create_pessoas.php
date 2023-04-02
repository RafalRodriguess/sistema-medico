<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePessoas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();


            $table->integer('personalidade');
            $table->integer('tipo');

            // Pessoa Física 
            $table->string('nome');
            $table->string('cpf');
            $table->string('telefone1');
            $table->string('telefone2');
            $table->string('email');
            $table->string('cep');
            $table->string('estado');
            $table->string('cidade');
            $table->string('bairro');
            $table->string('rua');

            // Pessoa Juridica
            $table->string('nome_fantasia')->nullable();
            $table->string('cnpj')->nullable();
            $table->string('razao_social')->nullable();
            $table->string('site')->nullable();
            $table->string('banco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('conta_corrente')->nullable();

            // Pessoa de Referencia
            $table->string('referencia_relacao')->nullable();
            $table->string('referencia_nome')->nullable();
            $table->string('referencia_telefone')->nullable();

            // Avaliação
            $table->integer('avaliação')->nullable();

            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');

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
        Schema::dropIfExists('pessoas');
    }
}
