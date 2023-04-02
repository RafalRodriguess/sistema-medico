<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsuarioIdTableOdontologicoPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('odontologicos_paciente', function (Blueprint $table) {
            $table->foreignId('avaliador_id')->after('prestador_id')->nullable()->default(null)->references('id')->on('instituicao_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('odontologicos_paciente', function (Blueprint $table) {
            
        });
    }
}
