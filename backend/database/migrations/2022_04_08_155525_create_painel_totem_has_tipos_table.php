<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePainelTotemHasTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('painel_totem_has_tipos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipos_chamada_id');
            $table->unsignedBigInteger('paineis_totem_id');
            $table->string('titulo');
            $table->string('local');
            $table->boolean('ativo')->default(false);

            $table->foreign('tipos_chamada_id', 'fk_ptht_tipos_chamada')->references('id')->on('tipos_chamada_totem')->onDelete('cascade');
            $table->foreign('paineis_totem_id', 'fk_ptht_paineis_totem')->references('id')->on('paineis_totem')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('painel_totem_has_tipos');
    }
}
