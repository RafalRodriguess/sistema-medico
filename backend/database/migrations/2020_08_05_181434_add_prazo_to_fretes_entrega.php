<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrazoToFretesEntrega extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fretes_entrega', function (Blueprint $table) {
            $table->enum('tipo_prazo', ['minutos', 'horas', 'dias'])->after('cep_fim');
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
        Schema::table('fretes_entrega', function (Blueprint $table) {
            $table->dropColumn('tipo_prazo');
            $table->dropColumn('prazo_maximo');
            $table->dropColumn('prazo_minimo');
        });
    }
}
