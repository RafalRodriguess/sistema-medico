<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePessoasAddIndexsOtimizacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->index(['nome']);
            $table->index(['asaplan_chave_plano']);
            //INDEX COMPOSTO DESCOBRIR DEPOIS
            // $table->index(['instituicao_id','asaplan_tipo','asaplan_chave_plano','asaplan_filial']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            //
        });
    }
}
