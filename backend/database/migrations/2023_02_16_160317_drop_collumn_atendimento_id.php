<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropCollumnAtendimentoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('altas_hospitalar', function (Blueprint $table) {
            $table->dropForeign('altas_hospitalar_atendimento_id_foreign');
            $table->dropColumn('atendimento_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('altas_hospitalar', function (Blueprint $table) {
            //
        });
    }
}
