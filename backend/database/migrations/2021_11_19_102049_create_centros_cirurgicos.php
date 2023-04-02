<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentrosCirurgicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centros_cirurgicos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->foreignId('cc_id')->references('id')->on('centros_de_custos');
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('centros_cirurgicos');
    }
}
