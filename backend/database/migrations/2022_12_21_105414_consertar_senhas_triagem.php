<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ConsertarSenhasTriagem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('senhas_triagem', function(Blueprint $table) {
            $table->dropForeign('senhas_triagem_usuarios_id_foreign');
            $table->dropColumn('usuarios_id');

            $table->unsignedBigInteger('pessoa_id')->nullable()->comment('vínculo com paciente');
            $table->foreign('pessoa_id', 'fk_senha_triagem_pessoa')->references('id')->on('pessoas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('senhas_triagem', function(Blueprint $table) {
            $table->dropForeign('fk_senha_triagem_pessoa');
            $table->dropColumn('pessoa_id');

            $table->unsignedBigInteger('usuarios_id')->nullable()->comment('vínculo com paciente');
            $table->foreign('usuarios_id', 'senhas_triagem_usuarios_id_foreign')->references('id')->on('usuarios');
        });
    }
}
