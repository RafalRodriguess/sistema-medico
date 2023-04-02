<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmVisualizarSetoresTableinstituicaoHasUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicao_has_usuarios', function (Blueprint $table) {
            $table->text('visualizar_setores')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicao_has_usuarios', function (Blueprint $table) {
            $table->dropColumn('visualizar_setores');
        });
    }
}
