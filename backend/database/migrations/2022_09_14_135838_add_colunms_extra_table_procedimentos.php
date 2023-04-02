<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmsExtraTableProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos', function (Blueprint $table) {
            $table->string('sexo')->nullable()->default(null);
            $table->tinyInteger('pacote')->nullable()->default(null);
            $table->integer('qtd_maxima')->nullable()->default(null);
            $table->string('tipo_servico')->nullable()->default(null);
            $table->string('tipo_consulta')->nullable()->default(null);
            $table->tinyInteger('recalcular')->nullable()->default(null);
            $table->tinyInteger('busca_ativa')->nullable()->default(null);
            $table->tinyInteger('parto')->nullable()->default(null);
            $table->tinyInteger('diaria_uti_rn')->nullable()->default(null);
            $table->tinyInteger('md_mt')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos', function (Blueprint $table) {
            $table->dropColumn('sexo');
            $table->dropColumn('pacote');
            $table->dropColumn('qtd_maxima');
            $table->dropColumn('tipo_servico');
            $table->dropColumn('tipo_consulta');
            $table->dropColumn('recalcular');
            $table->dropColumn('busca_ativa');
            $table->dropColumn('parto');
            $table->dropColumn('diaria_uti_rn');
            $table->dropColumn('md_mt');
        });
    }
}
