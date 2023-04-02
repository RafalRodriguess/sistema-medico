<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmsTableGruposProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('grupos_procedimentos', function (Blueprint $table) {
            $table->foreignId('grupo_faturamento_id')->nullable()->default(null)->references('id')->on('grupos_faturamento');
            $table->string('tipo')->nullable()->default(null);
            $table->tinyInteger('principal')->nullable()->default(0);
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
            $table->dropColumn('principal');
            $table->dropColumn('grupo_faturamento_id');
            $table->dropColumn('tipo');
        });
    }
}
