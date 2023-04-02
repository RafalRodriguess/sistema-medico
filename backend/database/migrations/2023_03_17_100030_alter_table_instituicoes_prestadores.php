<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInstituicoesPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            $table->string('nome')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            $table->dropColumn('nome');
        });
    }
}
