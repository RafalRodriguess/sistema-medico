<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColunmAlta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('internacoes', function (Blueprint $table) {
        //     $table->dropColumn('data_alta');
        //     $table->dropColumn('motivo_alta_id');
        //     $table->dropColumn('infeccao_alta');
        //     $table->dropColumn('procedimento_alta_id');
        //     $table->dropColumn('especialidade_alta_id');
        //     $table->dropColumn('obs_alta');
        //     $table->dropColumn('declaracao_obito_alta');
        //     $table->dropColumn('setor_alta_id');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            $table->dropColumn('data_alta');
            $table->dropColumn('motivo_alta_id');
            $table->dropColumn('infeccao_alta');
            $table->dropColumn('procedimento_alta_id');
            $table->dropColumn('especialidade_alta_id');
            $table->dropColumn('obs_alta');
            $table->dropColumn('declaracao_obito_alta');
            $table->dropColumn('setor_alta_id');
        });
    }
}
