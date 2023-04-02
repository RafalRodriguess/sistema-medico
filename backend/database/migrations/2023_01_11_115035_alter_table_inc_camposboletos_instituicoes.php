<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableIncCamposboletosInstituicoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->decimal('p_juros')->nullable();
            $table->decimal('p_multa')->nullable();
            $table->integer('dias_pagamento')->nullable();
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
            $table->dropColumn('p_juros');
            $table->dropColumn('p_multa');
            $table->dropColumn('dias_pagamento');
        });
    }
}
