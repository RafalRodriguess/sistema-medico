<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAtendimentosUrgenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_atendimentos_urgencia', function(Blueprint $table) {
            $table->unsignedBigInteger('local_procedencia_id')->comment('uma origem')->nullable()->change();
            $table->unsignedBigInteger('destino_id')->comment('uma origem')->nullable()->change();
            $table->dropColumn('same');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos_atendimentos_urgencia', function(Blueprint $table) {
            $table->string('same');
        });
    }
}
