<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConveniosControleRetornoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convenios_controle_retorno', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('convenios_id');
            $table->unsignedTinyInteger('grupo')->comment('id do grupo de campos o qual pertence, sendo 0 = ambulatorio, 1 = externo, 2 = urgencia');
            $table->unsignedTinyInteger('campo')->comment('id do campo, 0 = Prestador, 1 = Procedimento, 2 = Especialidade, 3 = Especialidade, 4 = Serviço, 5 = Livre, 6 = CID');

            $table->foreign('convenios_id')->references('id')->on('convenios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('convenios_controle_retorno');
    }
}
