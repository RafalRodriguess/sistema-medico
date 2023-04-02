<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableApresentacoesConvenio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $instituicao = DB::table('instituicoes')->first();
        if(!empty($instituicao)){
            Schema::table('apresentacoes_convenio', function (Blueprint $table) use($instituicao){
                $table->foreignId('instituicao_id')->default($instituicao->id)->after('id')->references('id')->on('instituicoes');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apresentacoes_convenio', function (Blueprint $table) {
            //
        });
    }
}
