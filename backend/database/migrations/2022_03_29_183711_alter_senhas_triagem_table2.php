<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSenhasTriagemTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('senhas_triagem', function (Blueprint $table) {
            $table->string('nome_mae')->nullable();
            $table->string('cpf')->nullable();
            $table->unsignedInteger('valor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('senhas_triagem', function (Blueprint $table) {
            $table->dropColumn('nome_mae');
            $table->dropColumn('cpf');
            $table->dropColumn('valor');
        });
    }
}
