<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInstituicaoMedicamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicao_medicamentos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->string('via_administracao');
            $table->string('nome');
            $table->string('forma_farmaceutica');
            $table->string('concentracao');
            $table->json('composicao')->nullable();
            $table->string('tipo')->comment('industrializado, manipulado');
            $table->tinyInteger('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instituicao_medicamentos');
    }
}
