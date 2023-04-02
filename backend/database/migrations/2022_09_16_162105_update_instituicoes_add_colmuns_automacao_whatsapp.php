<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInstituicoesAddColmunsAutomacaoWhatsapp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->tinyInteger('automacao_whatsapp')->nullable()->default(0);
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
            $table->dropColumn('automacao_whatsapp');
        });
    }
}
