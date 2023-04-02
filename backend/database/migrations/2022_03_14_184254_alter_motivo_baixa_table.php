<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterMotivoBaixaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('motivo_baixa', function (Blueprint $table) {
            $table->boolean('solicitacao_estoque')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('motivo_baixa', function (Blueprint $table) {
            $table->dropColumn('solicitacao_estoque');
        });
    }
}
