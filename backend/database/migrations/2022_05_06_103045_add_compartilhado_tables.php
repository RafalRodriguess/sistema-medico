<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompartilhadoTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('receituarios_paciente', function (Blueprint $table) {
            $table->tinyInteger('compartilhado')->default(0)->nullable();
        });
        Schema::table('atestados_paciente', function (Blueprint $table) {
            $table->tinyInteger('compartilhado')->default(0)->nullable();
        });
        Schema::table('relatorios_paciente', function (Blueprint $table) {
            $table->tinyInteger('compartilhado')->default(0)->nullable();
        });
        Schema::table('exames_paciente', function (Blueprint $table) {
            $table->tinyInteger('compartilhado')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('receituarios_paciente', function (Blueprint $table) {
            $table->dropColumn('compartilhado');
        });
        Schema::table('atestados_paciente', function (Blueprint $table) {
            $table->dropColumn('compartilhado');
        });
        Schema::table('relatorios_paciente', function (Blueprint $table) {
            $table->dropColumn('compartilhado');
        });
        Schema::table('exames_paciente', function (Blueprint $table) {
            $table->dropColumn('compartilhado');
        });
    }
}
