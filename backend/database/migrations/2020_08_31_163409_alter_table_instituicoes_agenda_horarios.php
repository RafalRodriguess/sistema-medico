<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableInstituicoesAgendaHorarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes_agenda', function (Blueprint $table) {

            // $table->enum('tipo', ['continuo', 'unico'])->nullable()->change();
            // $table->renameColumn('homra_fim', 'hora_fim')->change();
            $table->time('hora_fim')->nullable()->change();
            $table->json('dias_unicos')->nullable()->change();
            $table->foreignId('instituicoes_prestadores_id')->nullable()->default(null)->change();
            $table->foreignId('procedimentos_instituicoes_id')->nullable()->default(null)->change();
            DB::statement("ALTER TABLE `instituicoes_agenda` CHANGE `tipo` `tipo` ENUM('continuo', 'unico') default 'continuo';");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes_agenda', function (Blueprint $table) {
            $table->dropColumn('tipo');
            $table->dropColumn('hora_fim');
            $table->dropColumn('procedimentos_instituicoes_id');
            $table->dropColumn('instituicoes_prestadores_id');
        });
    }
}
