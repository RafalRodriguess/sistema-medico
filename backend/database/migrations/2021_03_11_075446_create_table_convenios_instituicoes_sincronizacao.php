<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableConveniosInstituicoesSincronizacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convenios_instituicoes_sincronizacao', function (Blueprint $table) {
            $table->id();
            $table->string('id_externo', '100');
            $table->foreignId('convenios_id')->references('id')->on('convenios');
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
        Schema::dropIfExists('convenios_instituicoes_sincronizacao');
    }
}
