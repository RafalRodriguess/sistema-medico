<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAlterIncColNumeroNotaNotasFiscasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->string('numero_nota')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->dropColumn('numero_nota')->nullable();
        });
    }
}
