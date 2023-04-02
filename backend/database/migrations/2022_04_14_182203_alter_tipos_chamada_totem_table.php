<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTiposChamadaTotemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tipos_chamada_totem', function(Blueprint $table) {
            $table->unsignedBigInteger('ganchos_id')->nullable();

            $table->foreign('ganchos_id', 'fk_tct_ganchos')->references('id')->on('ganchos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipos_chamada_totem', function(Blueprint $table) {
            $table->dropForeign('fk_tct_ganchos');
            $table->dropColumn('ganchos_id');
        });
    }
}
