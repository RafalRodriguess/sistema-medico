<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunsOdontologicoTableProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos', function (Blueprint $table) {
            $table->tinyInteger('odontologico')->nullable()->default(0);
            $table->tinyInteger('possui_regiao')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos', function (Blueprint $table) {
            $table->dropColumn('odontologico');
            $table->dropColumn('possui_regiao');
        });
    }
}
