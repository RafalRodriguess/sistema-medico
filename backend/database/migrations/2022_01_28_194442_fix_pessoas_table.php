<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FixPessoasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(in_array('nome', Schema::getColumnListing('setores_exame'))){
            Schema::table('setores_exame', function (Blueprint $table) {
                $table->dropColumn('nome');
            });
        }
        if(!in_array('nome', Schema::getColumnListing('pessoas'))){
            Schema::table('pessoas', function (Blueprint $table) {
                $table->string('nome');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
