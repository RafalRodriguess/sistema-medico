<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriateTableContas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instituicao_id')->references('id')->on("instituicoes");
            $table->string("descricao");
            $table->integer('tipo')->comment('1 - Caixa, 2 - Conta Corrente, 3 - Aplicação, 4 - Conta garantida, 5 - corretora');
            $table->string("banco")->nullable();
            $table->string("agencia")->nullable();
            $table->string("conta")->nullable();
            $table->integer('situacao')->comment('1 - ativo, 0 - Inativo');
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
        Schema::dropIfExists('contas');
    }
}
