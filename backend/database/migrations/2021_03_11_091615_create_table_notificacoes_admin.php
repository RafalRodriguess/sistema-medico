<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableNotificacoesAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificacoes_admin', function (Blueprint $table) {
            $table->id();
            $table->string('modulo', '100');
            $table->string('chave_instituicao', '100');
            $table->string('id_externo', '100');
            $table->string('descricao', '255');
            $table->tinyInteger('status')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notificacoes_admin');
    }
}
