<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnTipoTablePrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prestadores', function (Blueprint $table) {
            $table->integer('tipo')->nullable()->change();
            $table->json('vinculos')->nullable()->change();
            $table->dropColumn('nome_social');
            $table->dropColumn('nome_fantasma');
            $table->string('nome', '255')->nullable();
            $table->dropColumn('tipo_conselho_id');
            $table->integer('conselho_id')->nullable();
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
            $table->string('nome_social')->nullable();
            $table->string('nome_fantasma')->nullable();
            $table->dropColumn('nome');
            $table->integer('tipo_conselho_id')->nullable();
            $table->dropColumn('conselho_id');
        });
    }
}
