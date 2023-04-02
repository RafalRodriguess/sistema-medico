<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendamentosProcedimentosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'agendamentos_procedimentos';

    /**
     * Run the migrations.
     * @table agendamentos_procedimentos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            // $table->integer('agendamentos_id')->unsigned();
            // $table->integer('procedimentos_instituicoes_convenios_id')->unsigned();
            $table->decimal('valor_atual', 8, 2)->nullable();

            // $table->index(["procedimentos_instituicoes_convenios_id"], 'fk_agendamentos_has_procedimentos_instituicoes_convenios_pr_idx');

            // $table->index(["agendamentos_id"], 'fk_agendamentos_has_procedimentos_instituicoes_convenios_ag_idx');

            $table->softDeletes();
            $table->timestamps();

            $table->foreignId('agendamentos_id')
                ->references('id')->on('agendamentos')
                ->onDelete('no action')
                ->onUpdate('no action')->index('fk_agendamentos_has_procedimentos_instituicoes_convenios_ag_idx');

            $table->foreignId('procedimentos_instituicoes_convenios_id')
                ->references('id')->on('procedimentos_instituicoes_convenios')
                ->onDelete('no action')
                ->onUpdate('no action')->index('fk_agendamentos_has_procedimentos_instituicoes_convenios_pr_idx');
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
