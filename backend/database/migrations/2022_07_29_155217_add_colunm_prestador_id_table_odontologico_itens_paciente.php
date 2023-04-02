<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmPrestadorIdTableOdontologicoItensPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('odontologico_itens_paciente', function (Blueprint $table) {
            $table->foreignId('prestador_id')->nullable()->default(null)->references('id')->on('prestadores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('odontologico_itens_paciente', function (Blueprint $table) {
            $table->dropColumn('prestador_id');
        });
    }
}
