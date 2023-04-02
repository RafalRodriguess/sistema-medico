<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableEspecialidadesEspecializacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('especializacoes', function (Blueprint $table) {
            $table->foreignId('instituicoes_id')->nullable()->default(null)->references('id')->on('instituicoes');
        });

        Schema::table('especialidades', function (Blueprint $table) {
            $table->foreignId('instituicoes_id')->nullable()->default(null)->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            //
        });
    }
}
