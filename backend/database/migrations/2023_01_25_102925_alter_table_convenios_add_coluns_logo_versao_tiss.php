<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableConveniosAddColunsLogoVersaoTiss extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convenios', function (Blueprint $table) {
            $table->unsignedBigInteger('versao_tiss_id')->after('instituicao_id')->nullable();
            $table->string('imagem','255')->nullable();

            $table->foreign('versao_tiss_id', 'fk_convenios_versao_tiss_id')->references('id')->on('versoes_tiss');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convenios', function (Blueprint $table) {
            $table->dropForeign('fk_convenios_versao_tiss_id');
            $table->dropColumn('versao_tiss_id');
            $table->dropColumn('logo');
        });
    }
}
