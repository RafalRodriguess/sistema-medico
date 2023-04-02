<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChatContatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_contatos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicao_usuarios_remetente');
            $table->unsignedBigInteger('instituicao_usuarios_destinatario');
            $table->timestamp('ultima_mensagem')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedTinyInteger('prioridade')->default('10');

            $table->foreign('instituicao_usuarios_remetente', 'fk_contato_usuarios_remetente')->references('id')->on('instituicao_usuarios');
            $table->foreign('instituicao_usuarios_destinatario', 'fk_contato_usuarios_destinatario')->references('id')->on('instituicao_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_contatos');
    }
}
