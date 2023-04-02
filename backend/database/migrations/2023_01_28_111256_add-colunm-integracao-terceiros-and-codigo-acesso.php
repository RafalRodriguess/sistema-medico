<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmIntegracaoTerceirosAndCodigoAcesso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->string('possui_convenio_terceiros')->nullable()->default(0);
            $table->string('codigo_acesso_terceiros')->nullable()->default(null);
        });

        Schema::table('convenios', function (Blueprint $table) {
            $table->string('possui_terceiros')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->dropColumn('possui_convenio_terceiros');
            $table->dropColumn('codigo_acesso_terceiros');
        });

        Schema::table('convenios', function (Blueprint $table) {
            $table->dropColumn('possui_terceiros');
        });
    }
}
