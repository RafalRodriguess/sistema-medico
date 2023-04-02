<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProcedimenotsConveniosAddColObrigatorios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos_instituicoes_convenios', function (Blueprint $table) {
            $table->integer('utiliza_parametro_convenio')->default(1);
            $table->integer('carteirinha_obrigatoria')->default(0);
            $table->integer('aut_obrigatoria')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos_instituicoes_convenios', function (Blueprint $table) {
            $table->dropColumn('utiliza_parametro_convenio');
            $table->dropColumn('carteirinha_obrigatoria');
            $table->dropColumn('aut_obrigatoria');
        });
    }
}
