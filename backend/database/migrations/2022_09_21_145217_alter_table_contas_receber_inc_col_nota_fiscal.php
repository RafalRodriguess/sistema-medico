<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableContasReceberIncColNotaFiscal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->foreignId('nota_id')->nullable()->references('id')->on('notas_fiscais');
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
            $table->dropColumn('nota_id');
        });
    }
}
