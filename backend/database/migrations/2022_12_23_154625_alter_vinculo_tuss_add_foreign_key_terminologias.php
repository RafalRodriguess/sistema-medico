<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterVinculoTussAddForeignKeyTerminologias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vinculo_tuss', function (Blueprint $table) {
            $table->unsignedBigInteger('terminologia_id')->after('id');

            $table->integer('cod_terminologia')->nullable()->comment('utilizado na tabela 64');
            $table->string('forma_envio')->nullable()->comment('utilizado na tabela 64');
            $table->string('cod_grupo')->nullable()->comment('utilizado na tabela 64');
            $table->string('descricao_grupo')->nullable()->comment('utilizado na tabela 63 e 64');

            $table->foreign('terminologia_id', 'fk_vinculo_tuss_terminologia_id')->references('id')->on('vinculo_tuss_terminologias');
            
            $table->index('cod_termo', 'vinculo_tuss_cod_termo_index');
            $table->index('data_vigencia', 'vinculo_tuss_data_vigencia_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vinculo_tuss', function (Blueprint $table) {
            $table->dropForeign('fk_vinculo_tuss_terminologia_id');

            $table->dropColumn('terminologia_id');
            $table->dropColumn('cod_terminologia');
            $table->dropColumn('forma_envio');
            $table->dropColumn('cod_grupo');
            $table->dropColumn('descricao_grupo');

            $table->dropIndex('vinculo_tuss_cod_termo_index');
            $table->dropIndex('vinculo_tuss_data_vigencia_index');
        });
    }
}
