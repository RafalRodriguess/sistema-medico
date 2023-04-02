<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePacotesProcedimentosVinculos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pacotes_procedimentos_vinculos', function (Blueprint $table) {
            $table->foreignId('pacote_id')->references('id')->on('pacotes_procedimentos');
            $table->foreignId('procedimento_id')->references('id')->on('procedimentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pacotes_procedimentos_vinculos');
    }
}
