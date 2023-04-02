<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstituicoesAsaplanFiliais extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicoes_asaplan_filiais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instituicoes_id')->references('id')->on('instituicoes');
            $table->tinyInteger('cod_filial')->nullable();
            $table->string('database')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instituicoes_asaplan_filiais');
    }
}
