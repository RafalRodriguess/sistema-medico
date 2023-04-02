<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInstituicaHasPacientesSincronizacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicao_has_pacientes_sincronizacao', function (Blueprint $table) {
            $table->id();
            $table->dateTime('data_hora', 0)->nullable();
            // $table->index(["instituicao_has_pacientes_id"], 'fk_instituicoes_has_pacientes_sincronizacao1_idx');
            $table->foreignId('instituicao_has_pacientes_id')->references('id')->on('instituicao_has_pacientes')->index('fk_instituicoes_has_pacientes_sincronizacao1_idx');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instituicao_has_pacientes_sincronizacao');
    }
}
