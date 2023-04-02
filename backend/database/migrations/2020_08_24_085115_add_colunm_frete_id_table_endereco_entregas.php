<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmFreteIdTableEnderecoEntregas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('endereco_entregas', function (Blueprint $table) {
            $table->foreignId('frete_id_retirada')->nullable()->default(null)->references('id')->on('fretes_retirada')->after('referencia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('endereco_entregas', function (Blueprint $table) {
            $table->dropColumn('frete_id_retirada');
        });
    }
}
