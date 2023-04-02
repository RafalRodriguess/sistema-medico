<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEspecialidadesSincronizacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especialidades_sincronizacao', function (Blueprint $table) {
            $table->id();
            $table->string('id_externo', '100');
            $table->foreignId('especialidades_id')->references('id')->on('especialidades');
            $table->foreignId('instituicoes_id')->references('id')->on('instituicoes');
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
        Schema::dropIfExists('especialidades_sincronizacao');
    }
}
