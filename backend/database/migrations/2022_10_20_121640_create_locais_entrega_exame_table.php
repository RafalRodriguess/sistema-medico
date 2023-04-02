<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocaisEntregaExameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locais_entrega_exame', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->foreignId('instituicao_id', 'fk_locais_entrega_instituicao')
                ->references('id')
                ->on('instituicoes')
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
        Schema::dropIfExists('locais_entrega_exame');
    }
}
