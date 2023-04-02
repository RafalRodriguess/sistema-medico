<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToTableInstituicoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->string('razao_social', '255')->default(null)->after('cep');
            $table->string('cnpj', '18')->default(null)->after('cep');
            $table->string('inscricao_estadual', '255')->nullable()->default(null)->after('cep');
            $table->string('inscricao_municipal', '255')->nullable()->default(null)->after('cep');
            $table->integer('cnes')->nullable()->default(null)->after('cep');
            $table->integer('tipo')->default(1)->after('cep');
            $table->integer('ramo')->default(1)->after('cep');
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
            $table->dropColumn('razao_social');
            $table->dropColumn('cnpj');
            $table->dropColumn('inscricao_estadual');
            $table->dropColumn('inscricao_municipal');
            $table->dropColumn('cnes');
            $table->dropColumn('tipo');
            $table->dropColumn('ramo');
        });
    }
}
