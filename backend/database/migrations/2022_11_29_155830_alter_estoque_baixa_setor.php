<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEstoqueBaixaSetor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estoque_baixa', function(Blueprint $table) {
            $table->dropForeign('estoque_baixa_setor_id_foreign');
            $table->foreign('setor_id', 'fk_setor_exame_baixa')->references('id')->on('setores_exame');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
