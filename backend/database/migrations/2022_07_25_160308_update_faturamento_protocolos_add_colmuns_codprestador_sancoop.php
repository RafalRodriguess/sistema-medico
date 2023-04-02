<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFaturamentoProtocolosAddColmunsCodprestadorSancoop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faturamento_protocolos', function (Blueprint $table) {
            $table->foreignId('prestadores_id')->nullable()->references('id')->on('prestadores')->index('prestador_faturamento_protoclo_id_fk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faturamento_protocolos', function (Blueprint $table) {
            $table->dropColumn('prestadores_id');
        });
    }
}
