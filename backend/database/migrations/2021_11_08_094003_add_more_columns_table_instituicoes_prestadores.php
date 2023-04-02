<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsTableInstituicoesPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            $table->integer('carga_horaria_mensal')->nullable();
            $table->json('vinculos')->nullable();
            $table->string('pis')->nullable();
            $table->string('pasep')->nullable();
            $table->string('nir')->nullable();
            $table->string('proe')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            $table->dropColumn('carga_horaria_mensal');
            $table->dropColumn('vinculos');
            $table->dropColumn('pis');
            $table->dropColumn('pasep');
            $table->dropColumn('nir');
            $table->dropColumn('proe');
        });
    }
}
