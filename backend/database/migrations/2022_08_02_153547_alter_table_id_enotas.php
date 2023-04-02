<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableIdEnotas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->string('id_enotas')->nullable()->comment("Id para integração com o e-notas, vazio para instituição não cadastrada no e-notas");
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
            //
        });
    }
}
