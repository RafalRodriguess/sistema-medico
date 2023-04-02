<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAltasHospitalar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('altas_hospitalar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atendimento_id')->references('id')->on('agendamento_atendimentos');
            $table->foreignId('internacao_id')->references('id')->on('internacoes');
            $table->datetime('data_alta');
            $table->foreignId('motivo_alta_id')->references('id')->on('motivos_altas');
            $table->integer('status')->default(1)->comment("1 para alta ativa, 0 para alta cancelada");
            $table->integer('infeccao_alta')->comment('1 -> Sim 0 -> Não')->nullable();
            $table->foreignId('procedimento_alta_id')->nullable()->references('id')->on('procedimentos');
            $table->foreignId('especialidade_alta_id')->nullable()->references('id')->on('especialidades');
            $table->text('obs_alta')->nullable();
            $table->string('declaracao_obito_alta')->nullable();
            $table->foreignId('setor_alta_id')->nullable()->references('id')->on('setores');
            $table->string('motivo_cancel_alta')->nullable();
            $table->datetime('data_cancel_alta')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('altas_hospitalar');
    }
}
