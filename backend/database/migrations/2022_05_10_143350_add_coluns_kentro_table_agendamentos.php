<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunsKentroTableAgendamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->tinyInteger('envio_confirmacao_whatsapp')->default('0')->nullable();
            $table->datetime('data_hora_envio_confirmacao_whatsapp')->nullable();
            $table->string('resposta_confirmacao_whatsapp','255')->nullable();
            $table->datetime('data_hora_resposta_confirmacao_whatsapp')->nullable();
            $table->tinyInteger('envio_pesquisa_satisfacao_whatsapp')->default('0')->nullable();
            $table->datetime('data_hora_envio_pesquisa_satisfacao_whatsapp')->nullable();
            $table->string('resposta_pesquisa_satisfacao_whatsapp','255')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn('envio_confirmacao_whatsapp');
            $table->dropColumn('data_hora_envio_confirmacao_whatsapp');
            $table->dropColumn('resposta_confirmacao_whatsapp');
            $table->dropColumn('data_hora_resposta_confirmacao_whatsapp');
            $table->dropColumn('envio_pesquisa_satisfacao_whatsapp');
            $table->dropColumn('data_hora_envio_pesquisa_satisfacao_whatsapp');
            $table->dropColumn('resposta_pesquisa_satisfacao_whatsapp');
        });
    }
}
