<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMovimentacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimentacoes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->string('tipo_movimentacao', 100);
            $table->date('data');
            $table->foreignId('conta_id_origem')->references('id')->on('contas');
            $table->foreignId('conta_id_destino')->references('id')->on('contas');
            $table->decimal('valor', 10,2);
            $table->text('obs')->nullable();
            $table->foreignId('usuario_instituicao_id')->references('id')->on('instituicao_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimentacoes');
    }
}
