<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedimentosInstituicoesConveniosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'procedimentos_instituicoes_convenios';

    /**
     * Run the migrations.
     * @table procedimentos_instituicoes_convenios
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->decimal('valor', 8, 2)->nullable();
            // $table->integer('procedimentos_instituicoes_id')->unsigned();
            // $table->integer('convenios_id')->unsigned();

            // $table->index(["convenios_id"], 'fk_procedimentos_has_convenios_convenios1_idx');

            // $table->index(["procedimentos_instituicoes_id"], 'fk_procedimentos_instituicoes_convenios_procedimentos_insti_idx');


            $table->softDeletes();
            $table->timestamps();

            $table->foreignId('convenios_id')
                ->references('id')->on('convenios')
                ->onDelete('no action')
                ->onUpdate('no action')->index('fk_procedimentos_has_convenios_convenios1_idx');

            $table->foreignId('procedimentos_instituicoes_id')
                ->references('id')->on('procedimentos_instituicoes')
                ->onDelete('no action')
                ->onUpdate('no action')->index('fk_procedimentos_instituicoes_convenios_procedimentos_insti_idx');
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
