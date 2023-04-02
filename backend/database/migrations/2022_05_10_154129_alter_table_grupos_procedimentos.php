<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableGruposProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grupos_procedimentos', function (Blueprint $table) {
            $table->foreignId('instituicao_id')->nullable()->default(null)->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grupos_procedimentos', function (Blueprint $table) {
            //
        });
    }
}
