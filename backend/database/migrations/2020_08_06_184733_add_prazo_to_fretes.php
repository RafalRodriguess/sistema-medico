<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrazoToFretes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fretes', function (Blueprint $table) {
            $table->enum('tipo_prazo', ['minutos', 'horas', 'dias'])->after('ativado');
            $table->integer('prazo_minimo')->after('tipo_prazo');
            $table->integer('prazo_maximo')->after('prazo_minimo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fretes', function (Blueprint $table) {
            $table->dropColumn('tipo_prazo');
            $table->dropColumn('prazo_maximo');
            $table->dropColumn('prazo_minimo');
        });
    }
}
