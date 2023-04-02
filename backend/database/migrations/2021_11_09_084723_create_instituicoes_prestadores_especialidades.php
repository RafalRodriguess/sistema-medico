<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstituicoesPrestadoresEspecialidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inst_prest_especialidades', function (Blueprint $table) {
            $table->foreignId('instituicao_prestador_id')
                ->references('id')
                ->on('instituicoes_prestadores');
            $table->foreignId('especialidade_id')
                ->references('id')
                ->on('especialidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inst_prest_especialidades');
    }
}
