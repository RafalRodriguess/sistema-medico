<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableIncCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            $table->string('telefone2')->nullable();
            $table->integer('whatsapp_enviar_confirm_agenda')->default(0)->comment('0 -> inativo, 1->ativo');
            $table->integer('whatsapp_receber_agenda')->default(0)->comment('0 -> inativo, 1->ativo');
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
            $table->dropColumn('telefone2');
            $table->dropColumn('whatsapp_enviar_confirm_agenda');
            $table->dropColumn('whatsapp_receber_agenda');
        });
    }
}
