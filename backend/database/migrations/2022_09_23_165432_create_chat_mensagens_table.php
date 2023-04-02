<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChatMensagensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_mensagens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicao_usuarios_remetente');
            $table->unsignedBigInteger('instituicao_usuarios_destinatario');
            $table->text('mensagem');
            $table->timestamp('data_hora')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('instituicao_usuarios_remetente', 'fk_instituicao_usuarios_remetente')->references('id')->on('instituicao_usuarios');
            $table->foreign('instituicao_usuarios_destinatario', 'fk_instituicao_usuarios_destinatario')->references('id')->on('instituicao_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_mensagens');
    }
}
