<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVinculosSusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vinculos_sus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_procedimento')
                ->references('id')
                ->on('procedimentos')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->foreignId('id_sus')
                ->references('id')
                ->on('sus_tb_procedimento')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->foreignId('id_instituicao')
                ->references('id')
                ->on('instituicoes')
                ->onDelete('cascade')
                ->onUpdate('no action');

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
        Schema::dropIfExists('vinculos_sus');
    }
}
