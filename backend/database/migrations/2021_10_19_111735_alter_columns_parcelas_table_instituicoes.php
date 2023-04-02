<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsParcelasTableInstituicoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->integer('max_parcela')->nullable()->change();
            $table->integer('free_parcela')->nullable()->change();
            $table->decimal('valor_parcela', 4,2)->nullable()->change();
            $table->decimal('taxa_tectotum', 4,2)->nullable()->change();
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
            //
        });
    }
}
