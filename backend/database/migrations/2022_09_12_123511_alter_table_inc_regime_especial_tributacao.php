<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableIncRegimeEspecialTributacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuracoes_fiscais', function (Blueprint $table) {
            $table->integer('regime_especial_tributacao')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuracoes_fiscais', function (Blueprint $table) {
            $table->dropColumn('regime_especial_tributacao');
        });
    }
}
