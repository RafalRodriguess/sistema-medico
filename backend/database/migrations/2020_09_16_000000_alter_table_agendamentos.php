<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableAgendamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos', function (Blueprint $table) {

            $table->foreignId('usuario_id')->nullable()->default(null)->change();
            $table->decimal('porcento_parcela', 4,2)->nullable()->default(null)->change();
            $table->string('motivo_cancelamento',255)->nullable()->default(null)->after('codigo_transacao');


            $table->boolean('confirmacao_agendamento')->default(false);


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
            $table->dropColumn('tipo');
            $table->dropColumn('hora_fim');
            $table->dropColumn('procedimentos_instituicoes_id');
            $table->dropColumn('instituicoes_prestadores_id');
        });
    }
}
