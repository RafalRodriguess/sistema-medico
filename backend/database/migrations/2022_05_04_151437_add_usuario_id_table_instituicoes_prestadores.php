<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsuarioIdTableInstituicoesPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            $table->foreignId('instituicao_usuario_id')->after('especialidade_id')->nullable()->default(null)->references('id')->on('instituicao_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            //
        });
    }
}
