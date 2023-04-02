<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInternacaoAltaMedica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            $table->dateTime('data_alta')->nullable();
            $table->foreignId('motivo_alta_id')->nullable()->references('id')->on('motivos_altas');
            $table->integer('infeccao_alta')->comment('1 -> Sim 0 -> Não')->nullable();
            $table->foreignId('procedimento_alta_id')->nullable()->references('id')->on('procedimentos');
            $table->foreignId('especialidade_alta_id')->nullable()->references('id')->on('especialidades');
            $table->text('obs_alta')->nullable();
            $table->string('declaracao_obito_alta')->nullable();
            $table->foreignId('setor_alta_id')->nullable()->references('id')->on('setores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            //
        });
    }
}
