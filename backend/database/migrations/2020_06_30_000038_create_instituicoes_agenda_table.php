<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstituicoesAgendaTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'instituicoes_agenda';

    /**
     * Run the migrations.
     * @table instituicoes_agenda
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->enum('referente', ['prestador', 'procedimento'])->nullable();
            $table->enum('tipo', ['continuo', 'unico'])->nullable();
            $table->enum('dias_continuos', ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'])->nullable();
            $table->longText('dias_unicos')->nullable();
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fim')->nullable();
            $table->time('hora_intervalo')->nullable();
            $table->time('duracao_intervalo')->nullable();
            $table->time('duracao_atendimento')->nullable();
            // $table->integer('instituicoes_prestadores_id')->unsigned()->nullable();
            // $table->integer('procedimentos_instituicoes_id')->unsigned()->nullable();

            $table->index(["instituicoes_prestadores_id"], 'fk_instituicoes_agenda_instituicoes_prestadores1_idx');

            $table->index(["procedimentos_instituicoes_id"], 'fk_instituicoes_agenda_procedimentos_instituicoes1_idx');

            $table->softDeletes();
            $table->timestamps();

            $table->foreignId('instituicoes_prestadores_id', 'fk_instituicoes_agenda_instituicoes_prestadores1_idx')
                ->references('id')->on('instituicoes_prestadores')
                ->onDelete('no action')
                ->onUpdate('no action')->nullable();

            $table->foreignId('procedimentos_instituicoes_id', 'fk_instituicoes_agenda_procedimentos_instituicoes1_idx')
                ->references('id')->on('procedimentos_instituicoes')
                ->onDelete('no action')
                ->onUpdate('no action')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
       Schema::dropIfExists($this->tableName);
     }
}
