<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOdontologicoItemHasRegiao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odontologico_item_has_regiao', function (Blueprint $table) {
            $table->foreignId('odontologico_item_id')->index('odontologico_item_id_index_fk')->references('id')->on('odontologico_itens_paciente');
            $table->foreignId('regiao_id')->index('regiao_id_index_fk')->references('id')->on('regiao_procedimentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('odontologico_item_has_regiao');
    }
}
