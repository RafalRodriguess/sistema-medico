<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnUtilizaAgendaSetoresInstituicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setores_instituicoes_sincronizacao', function (Blueprint $table) {
            $table->tinyInteger('utiliza_agenda')->nullable()->default(0)->after('instituicoes_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setores_instituicoes_sincronizacao', function (Blueprint $table) {
            $table->dropColumn('utiliza_agenda');
        });
    }
}
