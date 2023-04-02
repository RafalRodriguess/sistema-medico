<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAltasInternacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('altas_internacao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internacao_id')->references('id')->on('internacoes');
            $table->dateTime('data_alta')->nullable();
            $table->foreignId('motivo_alta_id')->nullable()->references('id')->on('motivos_altas');
            $table->integer('infeccao_alta')->comment('1 -> Sim 0 -> Não')->nullable();
            $table->foreignId('procedimento_alta_id')->nullable()->references('id')->on('procedimentos');
            $table->foreignId('especialidade_alta_id')->nullable()->references('id')->on('especialidades');
            $table->text('obs_alta')->nullable();
            $table->string('declaracao_obito_alta')->nullable();
            $table->foreignId('setor_alta_id')->nullable()->references('id')->on('setores');
            $table->integer('status')->default(1)->nullable()->comment("1 -> alta ativa, 0 -> alta cancelada");
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
        Schema::dropIfExists('altas_internacao');
    }
}
