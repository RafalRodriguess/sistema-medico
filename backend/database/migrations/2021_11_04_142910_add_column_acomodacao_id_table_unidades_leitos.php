<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAcomodacaoIdTableUnidadesLeitos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unidades_leitos', function (Blueprint $table) {
            $table->foreignId('acomodacao_id')
                ->after('unidade_id')
                ->references('id')
                ->on('acomodacoes');
            $table->dropColumn('data_ativacao');
            $table->dropColumn('acomodacao');
            $table->json('caracteristicas')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unidades_leitos', function (Blueprint $table) {
            $table->dropForeign('acomodacao_id');
            $table->string('data_ativacao');
            $table->integer('acomodacao');
        });
    }
}
