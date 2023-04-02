<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmsEnderecoInstituicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->string('email','255')->nullable();
            $table->string('telefone','255')->nullable();
            $table->string('rua', '255')->nullable();
            $table->string('complemento', '255')->nullable();
            $table->string('numero', '25')->nullable();
            $table->string('bairro', '255')->nullable();
            $table->string('cidade', '255')->nullable();
            $table->string('estado', '255')->nullable();
            $table->string('cep', '10')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('telefone');
            $table->dropColumn('rua');
            $table->dropColumn('numero');
            $table->dropColumn('bairro');
            $table->dropColumn('cidade');
            $table->dropColumn('estado');
            $table->dropColumn('cep');

        });
    }
}
