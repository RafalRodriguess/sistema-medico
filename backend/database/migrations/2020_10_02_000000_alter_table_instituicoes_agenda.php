<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableInstituicoesAgenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes_agenda', function (Blueprint $table) {

            $table->foreignId('grupos_instituicoes_id')->nullable()->references('id')->on('grupos_instituicoes')->onDelete('no action')
                ->onUpdate('no action');


            DB::statement("ALTER TABLE `instituicoes_agenda` CHANGE `referente` `referente` ENUM('prestador', 'procedimento','grupo') ;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
