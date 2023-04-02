<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInstituicoesAddColmunsIntegracaoAsaplanDescricao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes_asaplan_filiais', function (Blueprint $table) {
            $table->string('descricao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes_asaplan_filiais', function (Blueprint $table) {
            $table->dropColumn('descricao');
        });
    }
}
