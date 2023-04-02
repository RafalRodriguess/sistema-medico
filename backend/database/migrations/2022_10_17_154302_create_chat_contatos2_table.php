<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatContatos2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('chat_contatos');
        Schema::create('chat_contatos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_origem');
            $table->unsignedBigInteger('usuario_contato');
            $table->unsignedBigInteger('ultima_mensagem_enviada')->nullable();
            $table->unsignedBigInteger('ultima_mensagem_recebida')->nullable();
            $table->unsignedTinyInteger('prioridade')->default(10);

            $table->foreign('usuario_origem', 'fk_contato_usuario_origem')->references('id')->on('instituicao_usuarios')->onDelete('CASCADE');
            $table->foreign('usuario_contato', 'fk_contato_usuario_contato')->references('id')->on('instituicao_usuarios')->onDelete('CASCADE');
            $table->foreign('ultima_mensagem_enviada', 'fk_contato_ultima_enviada')->references('id')->on('chat_mensagens')->onDelete('SET NULL');
            $table->foreign('ultima_mensagem_recebida', 'fk_contato_ultima_recebida')->references('id')->on('chat_mensagens')->onDelete('SET NULL');
        });
        Schema::table('chat_mensagens', function(Blueprint $table) {
            $table->renameColumn('visualizado', 'visualizada');
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
        Schema::table('chat_mensagens', function(Blueprint $table) {
            $table->renameColumn('visualizada', 'visualizado');
        });
    }
}
