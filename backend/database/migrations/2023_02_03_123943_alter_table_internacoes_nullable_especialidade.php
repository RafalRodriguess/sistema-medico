<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInternacoesNullableEspecialidade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            $table->foreignId('especialidade_id')->nullable()->change();
            $table->foreignId('leito_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            //
        });
    }
}
