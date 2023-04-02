<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableCirurgiasForeing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cirurgias', function (Blueprint $table) {
            $table->foreignId('grupo_cirurgia_id')->references('id')->on("grupos_cirurgias");
            $table->foreignId('tipo_anestesia_id')->nullable()->references('id')->on("tipos_anestesia");
            $table->foreignId('procedimento_id')->nullable()->references('id')->on("procedimentos");
            $table->foreignId('via_acesso_id')->nullable()->references('id')->on("vias_acesso"); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cirurgias', function (Blueprint $table) {
            //
        });
    }
}
