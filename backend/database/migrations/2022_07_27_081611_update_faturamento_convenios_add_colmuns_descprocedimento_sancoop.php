<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFaturamentoConveniosAddColmunsDescprocedimentoSancoop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos_instituicoes_convenios', function (Blueprint $table) {
            $table->string('sancoop_desc_procedimento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos_instituicoes_convenios', function (Blueprint $table) {
            $table->dropColumn('sancoop_desc_procedimento');
        });
    }
}
