<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusTbCidTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sus_tb_cid', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('CO_CID', 4);
$table->string('NO_CID', 100);
$table->char('TP_AGRAVO',1);
$table->char('TP_SEXO',1);
$table->char('TP_ESTADIO',1);
$table->unsignedSmallInteger('VL_CAMPOS_IRRADIADOS');


            $table->foreign('instituicoes_id', 'fk_sus_tb_cid_instituicao')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sus_tb_cid');
    }
}
