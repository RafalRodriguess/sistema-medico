<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsTableInstituicoesPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            // Caso o pretador seja PJ ----------------------------
            $table->string('nome_banco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('conta_bancaria')->nullable();
            // ----------------------------------------------------

            // Caso o pretador seja PF ----------------------------
            $table->boolean('ativo');
            $table->integer('tipo');
            // Caso o prestador seja do tipo médico ---------------
            $table->boolean('anestesista')->nullable();
            $table->boolean('auxiliar')->nullable();
            $table->integer('tipo_conselho_id')->nullable();
            // ----------------------------------------------------
            // ----------------------------------------------------    
            $table->dropForeign('instituicoes_prestadores_especialidades_id_foreign');
            $table->dropColumn('especialidades_id');

            $table->integer('especialidade_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            $table->dropColumn('nome_banco');
            $table->dropColumn('agencia');
            $table->dropColumn('conta_bancaria');

            $table->dropColumn('ativo');
            $table->dropColumn('tipo');

            $table->dropColumn('anestesista');
            $table->dropColumn('auxiliar');
            $table->dropColumn('tipo_conselho_id');
        });
    }
}
