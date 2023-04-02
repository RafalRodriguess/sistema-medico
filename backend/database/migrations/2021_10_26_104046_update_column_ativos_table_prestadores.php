<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnAtivosTablePrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestadores', function (Blueprint $table) {
            $table->integer('ativo')->change();
            $table->integer('anestesista')->change();
            $table->integer('auxiliar')->change();
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
            //
        });
    }
}
