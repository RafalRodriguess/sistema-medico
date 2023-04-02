<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInstituicaoIdTableAcomodacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acomodacoes', function (Blueprint $table) {
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acomodacoes', function (Blueprint $table) {
            $table->dropForeign('instituicao_id');
            $table->dropColumn('instituicao_id');
        });
    }
}
