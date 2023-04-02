<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePerfilUsuarioInstituicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('perfis_usuarios_instituicoes_habilidades', function (Blueprint $table) {
            $table->dropForeign('perfis_usuarios_instituicoes_habilidades_usuario_id_foreign');
            $table->dropColumn('usuario_id');
            $table->foreignId('perfil_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perfis_usuarios_instituicoes', function (Blueprint $table) {
            //
        });
    }
}
