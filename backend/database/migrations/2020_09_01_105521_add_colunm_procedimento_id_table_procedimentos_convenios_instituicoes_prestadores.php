<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmProcedimentoIdTableProcedimentosConveniosInstituicoesPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos_convenios_instituicoes_prestadores', function (Blueprint $table) {

            $table->foreignId('procedimentos_id')->references('id')->on('procedimentos')
            ->onDelete('no action')
            ->onUpdate('no action')->index('fk_procedimentos_insti_idx');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos_convenios_instituicoes_prestadores', function (Blueprint $table) {
            $table->dropColumn('procedimentos_id');
        });
    }
}
