<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveMoreColumnsTablePrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestadores', function (Blueprint $table) {
            $table->dropColumn('carga_horaria_mensal');
            $table->dropColumn('vinculos');
            $table->dropColumn('especialidades');
            $table->dropColumn('pis');
            $table->dropColumn('pasep');
            $table->dropColumn('nir');
            $table->dropColumn('proe');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestadores', function (Blueprint $table) {
            $table->integer('carga_horaria_mensal');
            $table->json('vinculos');
            $table->json('especialidades');
            $table->string('pis');
            $table->string('pasep');
            $table->string('nir');
            $table->string('proe');
        });
    }
}
