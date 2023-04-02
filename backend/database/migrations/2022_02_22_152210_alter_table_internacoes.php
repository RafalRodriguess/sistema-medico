<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInternacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            $table->foreignId('internacao_id')->nullable()->references('id')->on('internacoes');
            $table->integer('status')->comment('1 -> ativo, 0 -> inativo')->nullable();
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
