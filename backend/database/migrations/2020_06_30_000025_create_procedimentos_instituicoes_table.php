<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedimentosInstituicoesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'procedimentos_instituicoes';

    /**
     * Run the migrations.
     * @table procedimentos_instituioces
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            // $table->integer('procedimentos_id')->unsigned();
            // $table->integer('instituicoes_id')->unsigned();

            $table->index(["instituicoes_id"], 'fk_procedimentos_instituicoes_instituicoes1_idx');

            $table->index(["procedimentos_id"], 'fk_procedimentos_instituicoes_procedimentos1_idx');


            $table->foreignId('procedimentos_id', 'fk_procedimentos_instituicoes_procedimentos1_idx')
                ->references('id')->on('procedimentos')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreignId('instituicoes_id', 'fk_procedimentos_instituicoes_instituicoes1_idx')
                ->references('id')->on('instituicoes')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->softDeletes();
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
       Schema::dropIfExists($this->tableName);
     }
}
