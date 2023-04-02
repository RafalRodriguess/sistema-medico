<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddColumnsGestorHospitalar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convenios', function (Blueprint $table) {
            $table->string('razao_social', 255)->nullable();
            $table->string('responsavel', 155)->nullable();
            $table->string('cargo_responsavel', 155)->nullable();
            $table->string('email', 155)->nullable();
            $table->timestamp('dt_inicio_contrato')->nullable();
            $table->string('endereco', 255)->nullable();
            $table->string('fone_contato', 155)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convenios', function (Blueprint $table) {
            //
        });
    }
}
