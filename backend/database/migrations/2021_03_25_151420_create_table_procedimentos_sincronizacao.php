<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProcedimentosSincronizacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimentos_sincronizacao', function (Blueprint $table) {
            $table->id();
            $table->string('id_externo', '100');
            $table->foreignId('procedimentos_id')->references('id')->on('procedimentos');
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
        Schema::dropIfExists('procedimentos_sincronizacao');
    }
}
