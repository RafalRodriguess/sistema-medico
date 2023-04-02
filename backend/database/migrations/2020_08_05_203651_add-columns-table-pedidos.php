<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsTablePedidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('prazo_entrega');
            $table->enum('prazo_tipo', ['minutos', 'horas', 'dias'])->nullable()->after('codigo_transacao');
            $table->integer('prazo_maximo')->after('prazo_tipo')->nullable();
            $table->integer('prazo_minimo')->after('prazo_maximo')->nullable();
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
            $table->dateTime('prazo_entrega');
            $table->dropColumn('prazo_tipo');
            $table->dropColumn('prazo_maximo');
            $table->dropColumn('prazo_minimo');
        });
    }
}
