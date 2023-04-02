<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgendamentosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'agendamentos';

    /**
     * Run the migrations.
     * @table agendamentos
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->enum('tipo', ['agendamento', 'pre_agendamento'])->nullable();
            $table->dateTime('data')->nullable();
            $table->enum('status', ['agendado', 'confirmado', 'cancelado', 'pendente', 'finalizado'])->nullable();
            // $table->integer('instituicoes_agenda_id')->unsigned();
            $table->decimal('valor_total', 8, 2)->nullable();

            $table->index(["instituicoes_agenda_id"], 'fk_agendamentos_instituicoes_agenda1_idx');

            $table->softDeletes();
            $table->timestamps();

            $table->foreignId('instituicoes_agenda_id', 'fk_agendamentos_instituicoes_agenda1_idx')
                ->references('id')->on('instituicoes_agenda')
                ->onDelete('no action')
                ->onUpdate('no action');
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
