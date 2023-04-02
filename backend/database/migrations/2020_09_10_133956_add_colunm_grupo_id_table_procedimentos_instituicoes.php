<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmGrupoIdTableProcedimentosInstituicoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos_instituicoes', function (Blueprint $table) {
            $table->foreignId('grupo_id')->after('instituicoes_id')->references('id')->on('grupos_procedimentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos_instituicoes', function (Blueprint $table) {
            $table->dropColumn('grupo_id');
        });
    }
}
