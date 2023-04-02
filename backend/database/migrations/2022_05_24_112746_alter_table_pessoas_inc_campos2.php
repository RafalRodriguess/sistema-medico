<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePessoasIncCampos2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->string('telefone3')->nullable()->after('telefone2');
            $table->string('naturalidade')->nullable()->after('numero');
            $table->string('indicacao_descricao')->nullable()->after('naturalidade');
            $table->string('profissao')->nullable()->after('indicacao_descricao');
            $table->string('referencia_documento')->nullable()->after('referencia_telefone');
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
