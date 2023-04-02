<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUsuarioColumnInventarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estoque_inventario', function(Blueprint $table) {
            $table->unsignedBigInteger('usuario_id')->nullable()->comment('usuário que fechou o inventario');
            $table->foreign('usuario_id', 'fk_inventario_usuario')->references('id')->on('instituicao_usuarios')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estoque_inventario', function(Blueprint $table) {
            $table->dropForeign('fk_inventario_usuario');
            $table->dropColumn('usuario_id');
        });
    }
}
