<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableIncCampoMotivoStatusNotasFiscasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->string('motivo_status')->nullable();
            $table->longText('json_nfe')->nullable();
            $table->longText('xml_nfe')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notas_fiscais', function (Blueprint $table) {
            $table->dropColumn('motivo_status');
            $table->dropColumn('json_nfe');
            $table->dropColumn('xml_nfe');
        });
    }
}
