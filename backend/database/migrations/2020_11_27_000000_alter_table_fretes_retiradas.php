<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableFretesRetiradas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fretes_retirada', function (Blueprint $table) {

            $table->enum('tipo_prazo_minimo', ['minutos', 'horas', 'dias'])->after('cep')->after('tipo_prazo');
            $table->enum('tipo_prazo_maximo', ['minutos', 'horas', 'dias'])->after('cep')->after('tipo_prazo_minimo');
            $table->dropColumn('tipo_prazo');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('fretes_retirada', function (Blueprint $table) {
            $table->dropColumn('tipo_prazo_minimo');
            $table->dropColumn('tipo_prazo_maximo');
         });
    }
}
