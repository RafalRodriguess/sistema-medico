<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProcedimentosInstituicoesTable extends Migration
{
    private $tableName = 'procedimentos_instituicoes';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->tableName, function (Blueprint $table) {
            $table->unsignedBigInteger('modalidades_exame_id')->nullable();

            $table->foreign('modalidades_exame_id')->references('id')->on('modalidades_exame');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
