<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCentroCustoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('centros_de_custos', function (Blueprint $table) {
            $table->unsignedBigInteger('setor_exame_id')->nullable();
            $table->foreign('setor_exame_id')->references('id')->on('setores_exame');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('centros_de_custos', function (Blueprint $table) {
            $table->dropForeign(['setor_exame_id']);
            $table->dropColumn('setor_exame_id');
        });
    }
}
