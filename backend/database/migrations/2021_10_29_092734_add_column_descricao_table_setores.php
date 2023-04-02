<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnDescricaoTableSetores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('setores', function (Blueprint $table) {
            $table->string('descricao', '512')->nullable()->after('nome');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setores', function (Blueprint $table) {
            $table->dropColumn('descricao');
        });
    }
}
