<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCamposColunaRegiaoProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('regiao_procedimentos')->insert([
            'descricao' => 'Raiz'
        ]);
        DB::table('regiao_procedimentos')->insert([
            'descricao' => 'Coroa'
        ]);
        DB::table('regiao_procedimentos')->insert([
            'descricao' => 'Colo'
        ]);
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
