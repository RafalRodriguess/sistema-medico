<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChamadasTotemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chamadas_totem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('senhas_triagem_id');
            $table->unsignedTinyInteger('origem_chamada');
            $table->unsignedBigInteger('instituicoes_id');
            $table->boolean('etapa_completa')->default(false);
            $table->string('local')->nullable();
            $table->timestamps();
            
            $table->foreign('senhas_triagem_id', 'fk_ct_senhas_triagem_id')->references('id')->on('senhas_triagem');
            $table->foreign('instituicoes_id', 'fk_ct_instituicoes_id')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chamadas_totem');
    }
}
