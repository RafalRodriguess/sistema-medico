<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOdontologicoItensPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odontologico_itens_paciente', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('odontologico_paciente_id')->references('id')->on('odontologicos_paciente');
            $table->string('status')->nullable()->comment('aprovado', 'reprovado')->default('aprovado');
            $table->decimal('valor', 10,2)->nullable();
            $table->integer('dente_id');
            $table->foreignId('procedimento_instituicao_convenio_id')->references('id')->on('procedimentos_instituicoes_convenios')->index('procedimento_inst_convenio_odontologio_item');
            $table->foreignId('regiao_procedimento_id')->nullable()->default(null)->references('id')->on('regiao_procedimentos');
            $table->tinyInteger('concluido')->nullable()->default(0);
            $table->date('data_conclusao')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('odontologico_itens_paciente');
    }
}
