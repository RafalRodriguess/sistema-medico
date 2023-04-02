<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInstituicoesAddColmunsWhatsappAniversarioIntegracaoAsaplan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->string('automacao_whatsapp_aniversario')->nullable()->default(0);
            $table->string('integracao_asaplan')->nullable()->default(0);
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
            $table->dropColumn('automacao_whatsapp_aniversario');
            $table->dropColumn('integracao_asaplan');
        });
    }
}
