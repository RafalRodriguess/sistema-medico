<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnsTablePrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestadores', function (Blueprint $table) {
            $table->dropColumn('nome_banco');
            $table->dropColumn('agencia');
            $table->dropColumn('conta_bancaria');

            $table->dropColumn('ativo');
            $table->dropColumn('tipo');
            
            $table->dropColumn('anestesista');
            $table->dropColumn('auxiliar');
            $table->dropColumn('conselho_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestadores', function (Blueprint $table) {
            $table->string('nome_banco')->nullable();
            $table->string('agencia')->nullable();
            $table->string('conta_bancaria')->nullable();
            $table->boolean('ativo');
            $table->integer('tipo');
            $table->boolean('anestesista')->nullable();
            $table->boolean('auxiliar')->nullable();
            $table->integer('conselho_id')->nullable();
        });
    }
}
