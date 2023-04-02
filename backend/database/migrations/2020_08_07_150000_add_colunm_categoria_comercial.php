<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmCategoriaComercial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('comerciais', function (Blueprint $table) {
            $table->enum('categoria', ['drogaria','ortopedico'])->after('nome_fantasia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('comerciais', function (Blueprint $table) {
            $table->dropColumn('categoria');
        });
    }
}
