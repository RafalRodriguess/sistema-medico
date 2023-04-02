<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCamposToNullableTablePessoas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->string('cpf')->nullable()->change();
            $table->string('telefone1')->nullable()->change();
            $table->string('telefone2')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('cep')->nullable()->change();
            $table->string('estado')->nullable()->change();
            $table->string('cidade')->nullable()->change();
            $table->string('bairro')->nullable()->change();
            $table->string('rua')->nullable()->change();
            $table->string('numero')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            
        });
    }
}
