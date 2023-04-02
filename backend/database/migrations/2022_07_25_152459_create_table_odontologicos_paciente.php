<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOdontologicosPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('odontologicos_paciente', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('paciente_id')->references('id')->on('pessoas');
            $table->foreignId('agendamento_id')->nullable()->default(null)->references('id')->on('agendamentos');
            $table->string('status')->nullable()->comment('criado','aprovado','reprovado')->default('criado');
            $table->decimal('valor_total', 10,2)->nullable();
            $table->decimal('valor_aprovado', 10,2)->nullable();
            $table->decimal('desconto', 10,2)->nullable();
            $table->foreignId('prestador_id')->nullable()->default(null)->references('id')->on('prestadores');
            $table->foreignId('responsavel_id')->nullable()->default(null)->references('id')->on('instituicao_usuarios');
            $table->foreignId('negociador_id')->nullable()->default(null)->references('id')->on('instituicao_usuarios');
            $table->date('data_aprovacao')->nullable()->default(null);
            $table->date('data_reprovacao')->nullable()->default(null);
            $table->tinyInteger('finalizado')->nullable()->default(0);
            $table->date('data_finalizado')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('odontologicos_paciente');
    }
}
