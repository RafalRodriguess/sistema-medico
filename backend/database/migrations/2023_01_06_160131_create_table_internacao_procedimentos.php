<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInternacaoProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internacao_procedimentos', function (Blueprint $table) {
            $table->foreignId('internacao_id')->references('id')->on('internacoes');
            $table->foreignId('convenio_id')->references('id')->on('convenios');
            $table->foreignId('proc_conv_id')->references('id')->on('procedimentos_instituicoes_convenios');
            $table->integer('quantidade_procedimento')->default(1);
            $table->decimal('valor', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internacao_procedimentos');
    }
}
