<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrioridadeClassificacaoTriagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classificacoes_triagem', function(Blueprint $table) {
            $table->unsignedTinyInteger('prioridade')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classificacoes_triagem', function(Blueprint $table) {
            $table->dropColumn('prioridade');
        });
    }
}
