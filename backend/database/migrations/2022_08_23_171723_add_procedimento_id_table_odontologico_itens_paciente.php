<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcedimentoIdTableOdontologicoItensPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('odontologico_itens_paciente', function (Blueprint $table) {
            $table->foreignId('procedimento_id')->nullable()->default(null)->references('id')->on('procedimentos');
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
            //
        });
    }
}
