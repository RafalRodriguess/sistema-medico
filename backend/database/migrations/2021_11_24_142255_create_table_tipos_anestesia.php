<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTiposAnestesia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipos_anestesia', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->integer('cobranca_aih')->default(0)->comment("0 -> Não permite | 1 -> permite");
            $table->foreignId('instituicao_id')->unsigned()->references('id')->on('instituicoes');
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
        Schema::dropIfExists('tipos_anestesia');
    }
}
