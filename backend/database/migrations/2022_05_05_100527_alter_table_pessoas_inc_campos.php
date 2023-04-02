<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePessoasIncCampos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->string('nome_pai')->nullable()->before('cpf');
            $table->string('nome_mae')->nullable()->before('cpf');
            $table->string('identidade')->nullable()->before('cpf');
            $table->string('orgao_expedidor')->nullable()->before('cpf');
            $table->date('data_emissao')->nullable()->before('cpf');
            $table->string('estado_civil')->nullable()->before('cpf');
            $table->string('sexo')->length(1)->nullable()->before('cpf');
            $table->date('nascimento')->nullable()->before('cpf');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            //
        });
    }
}
