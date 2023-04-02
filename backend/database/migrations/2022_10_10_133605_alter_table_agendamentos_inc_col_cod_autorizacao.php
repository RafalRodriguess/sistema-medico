<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAgendamentosIncColCodAutorizacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->string('cod_aut_convenio')->nullable();
            $table->string('num_guia_convenio')->nullable();
            $table->string('arquivo_guia_convenio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn('cod_aut_convenio');
            $table->dropColumn('arquivo_guia_convenio');
        });
    }
}
