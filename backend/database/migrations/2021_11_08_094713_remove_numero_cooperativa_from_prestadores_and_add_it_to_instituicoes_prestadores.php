<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNumeroCooperativaFromPrestadoresAndAddItToInstituicoesPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestadores', function (Blueprint $table) {
            $table->dropColumn('numero_cooperativa');
        });

        Schema::table('instituicoes_prestadores', function (Blueprint $table){
            $table->string('numero_cooperativa')->nullable();
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
            $table->string('numero_cooperativa')->nullable();
        });

        Schema::table('instituicoes_prestadores', function (Blueprint $table){
            $table->dropColumn('numero_cooperativa');
        });
    }
}
