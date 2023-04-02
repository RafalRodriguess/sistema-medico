<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunsKentroTableInstituicoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->integer('kentro_fila_empresa')->nullable();;
            $table->integer('kentro_confirmacao_usuario')->nullable();;
            $table->text('kentro_msg_confirmacao')->nullable();;
            $table->text('kentro_msg_resposta_confirmacao')->nullable();;
            $table->text('kentro_msg_resposta_desmarcado')->nullable();;
            $table->text('kentro_msg_resposta_remarcacao')->nullable();;
            $table->text('kentro_msg_pesquisa_satisfacao')->nullable();;
            $table->text('kentro_msg_resposta_pesquisa_satisfacao')->nullable();;
            $table->text('kentro_msg_aniversario')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->dropColumn('kentro_fila_empresa');
            $table->dropColumn('kentro_confirmacao_usuario');
            $table->dropColumn('kentro_msg_confirmacao');
            $table->dropColumn('kentro_msg_resposta_confirmacao');
            $table->dropColumn('kentro_msg_resposta_desmarcado');
            $table->dropColumn('kentro_msg_resposta_remarcacao');
            $table->dropColumn('kentro_msg_pesquisa_satisfacao');
            $table->dropColumn('kentro_msg_resposta_pesquisa_satisfacao');
            $table->dropColumn('kentro_msg_aniversario');
        });
    }
}
