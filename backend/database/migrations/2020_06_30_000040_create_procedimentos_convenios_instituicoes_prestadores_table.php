<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcedimentosConveniosInstituicoesPrestadoresTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'procedimentos_convenios_instituicoes_prestadores';

    /**
     * Run the migrations.
     * @table procedimentos_convenios_instituicoes_prestadores
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            // $table->integer('instituicoes_prestadores_id')->unsigned();
            // $table->integer('procedimentos_convenios_id')->unsigned();

            // $table->index(["instituicoes_prestadores_id"], 'fk_instituicoes_prestadores_has_procedimentos_convenios_ins_idx');

            // $table->index(["procedimentos_convenios_id"], 'fk_instituicoes_prestadores_has_procedimentos_convenios_pro_idx');

            $table->softDeletes();
            $table->timestamps();

            $table->foreignId('instituicoes_prestadores_id')
                ->references('id')->on('instituicoes_prestadores')
                ->onDelete('no action')
                ->onUpdate('no action')->index('fk_instituicoes_prestadores_has_procedimentos_convenios_ins_idx');

            $table->foreignId('procedimentos_convenios_id')
                ->references('id')->on('procedimentos_instituicoes_convenios')
                ->onDelete('no action')
                ->onUpdate('no action')->index('fk_instituicoes_prestadores_has_procedimentos_convenios_pro_idx');
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
