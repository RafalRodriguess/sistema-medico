<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmRegraCobrancaIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convenios_planos', function (Blueprint $table) {
            $table->foreignId("regra_cobranca_id")->nullable()->default(null)->references('id')->on('regras_cobranca');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convenios_planos', function (Blueprint $table) {
            //
        });
    }
}
