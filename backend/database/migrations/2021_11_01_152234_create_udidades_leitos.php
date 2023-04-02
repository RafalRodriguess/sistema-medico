<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUdidadesLeitos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unidades_leitos', function (Blueprint $table) {
            $table->id();
            $table->integer('quantidade');
            $table->string('descricao');
            $table->integer('tipo');
            $table->integer('situacao');
            $table->integer('acomodacao');
            $table->string('sala');
            $table->string('data_ativacao');
            $table->string('data_desativacao')->nullable();
            $table->foreignId('especialidade_id')
                ->nullable()->references('id')->on('especialidades');
            $table->foreignId('medico_id')->nullable()
                ->nullable()->references('id')->on('prestadores');
            $table->string('caracteristicas');
            $table->foreignId('unidade_id')->references('id')->on('unidades_internacoes');
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
        Schema::dropIfExists('unidades_leitos');
    }
}
