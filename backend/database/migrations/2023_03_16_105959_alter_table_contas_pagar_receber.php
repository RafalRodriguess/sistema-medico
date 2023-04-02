<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableContasPagarReceber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->foreignId('usuario_baixou_id')->nullable()->references('id')->on('instituicao_usuarios');
        });

        Schema::table('contas_pagar', function (Blueprint $table) {
            $table->foreignId('usuario_baixou_id')->nullable()->references('id')->on('instituicao_usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->dropColumn('usuario_baixou_id');
        });

        Schema::table('contas_pagar', function (Blueprint $table) {
            $table->dropColumn('usuario_baixou_id');
        });
    }
}
