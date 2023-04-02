<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInstituicaoUsuariosIncPefilId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicao_usuarios', function (Blueprint $table) {
            $table->foreignId('perfil_id')->nullable()->references('id')->on('perfis_usuarios_instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicao_usuarios', function (Blueprint $table) {
            //
        });
    }
}
