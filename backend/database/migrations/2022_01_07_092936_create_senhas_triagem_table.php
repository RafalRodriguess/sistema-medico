<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSenhasTriagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('senhas_triagem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filas_totem_id');
            $table->unsignedBigInteger('classificacoes_triagem_id')->nullable();
            $table->text('queixa')->nullable();
            $table->string('sinais_vitais')->nullable();
            $table->unsignedBigInteger('usuarios_id')->nullable()->comment('Identificador do paciente caso esteja cadastrado');
            $table->string('nome_paciente')->nullable()->comment('Nome do paciente caso este não esteja cadastrado no sistema');
            $table->timestamp('horario_triagem')->nullable();
            $table->timestamp('horario_retirada')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('filas_totem_id')->references('id')->on('filas_totem')->onDelete('cascade');
            $table->foreign('classificacoes_triagem_id')->references('id')->on('classificacoes_triagem')->onDelete('set null');
            $table->foreign('usuarios_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('senhas_triagem');
    }
}
