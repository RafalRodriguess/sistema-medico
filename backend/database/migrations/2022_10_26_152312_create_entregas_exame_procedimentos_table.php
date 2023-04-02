<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntregasExameProcedimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entregas_exame_procedimentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('entrega_exame_id');
            $table->unsignedBigInteger('procedimentos_instituicao_id');

            $table->foreign('entrega_exame_id', 'fk_entrega_exame_procedimento')
                ->references('id')
                ->on('entregas_exame')
                ->onDelete('cascade');

            $table->foreign('procedimentos_instituicao_id', 'fk_eep_procedimentos_instituicao')
                ->references('id')
                ->on('procedimentos_instituicoes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entregas_exame_procedimentos');
    }
}
