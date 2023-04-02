<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CpfAcompanhante extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->string('cpf_acompanhante')->nullable()->after('acompanhante_telefone');
        });

        Schema::table('configuracoes_fiscais', function (Blueprint $table) {
            $table->string('senha_certificado')->nullable()->change();
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
            $table->dropColumn('cpf_acompanhate');
        });
    }
}
