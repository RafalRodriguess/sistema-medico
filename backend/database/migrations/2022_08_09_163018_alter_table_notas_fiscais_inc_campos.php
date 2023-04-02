<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableNotasFiscaisIncCampos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->string("cliente_nome")->nullable();
            $table->string("cliente_email")->nullable();
            $table->string("cliente_cpfCnpj")->nullable();
            $table->string("cliente_inscricaoMunicipal")->nullable();
            $table->string("cliente_inscricaoEstadual")->nullable();
            $table->string("cliente_telefone")->nullable();
            
            $table->string("cliente_pais")->nullable();
            $table->string("cliente_uf")->nullable();
            $table->string("cliente_cidade")->nullable();
            $table->string("cliente_logradouro")->nullable();
            $table->string("cliente_numero")->nullable();
            $table->string("cliente_complemento")->nullable();
            $table->string("cliente_bairro")->nullable();
            $table->string("cliente_cep")->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            //
        });
    }
}
