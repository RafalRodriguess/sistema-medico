<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGanchosDadosInstituicaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ganchos_dados_instituicao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ganchos_id');
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('model');
            $table->json('dados')->nullable();

            $table->foreign('ganchos_id', 'fk_gdi_ganchos')->references('id')->on('ganchos')->onDelete('cascade');
            $table->foreign('instituicoes_id', 'fk_gdi_instituicoes')->references('id')->on('instituicoes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ganchos_dados_instituicao');
    }
}
