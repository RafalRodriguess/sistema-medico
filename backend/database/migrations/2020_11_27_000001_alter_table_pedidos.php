<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTablePedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {

            $table->enum('prazo_tipo_minimo', ['minutos', 'horas', 'dias'])->nullable()->after('codigo_transacao');
            $table->enum('prazo_tipo_maximo', ['minutos', 'horas', 'dias'])->nullable()->after('prazo_tipo_minimo');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('prazo_tipo_minimo');
            $table->dropColumn('prazo_tipo_maximo');
        });
    }
}
