<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstituicoesPrestadoresTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'instituicoes_prestadores';

    /**
     * Run the migrations.
     * @table instituicoes_prestadores
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            // $table->integer('instituicoes_id')->unsigned();
            // $table->unsignedBigInteger('prestadores_id');
            // $table->unsignedBigInteger('especialidades_id');

            $table->index(["especialidades_id"], 'fk_instituicoes_prestadores_especialidades1_idx');

            $table->index(["prestadores_id"], 'fk_instituicoes_has_prestadores_prestadores1_idx');

            $table->index(["instituicoes_id"], 'fk_instituicoes_has_prestadores_instituicoes1_idx');


            $table->foreignId('instituicoes_id', 'fk_instituicoes_has_prestadores_instituicoes1_idx')
                ->references('id')->on('instituicoes')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreignId('prestadores_id', 'fk_instituicoes_has_prestadores_prestadores1_idx')
                ->references('id')->on('prestadores')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreignId('especialidades_id', 'fk_instituicoes_prestadores_especialidades1_idx')
                ->references('id')->on('especialidades')
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
