<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorDescontoMaximoTableInstituicaoHasUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicao_has_usuarios', function (Blueprint $table) {
            $table->decimal('desconto_maximo', 5,2)->nullable()->default(100.00);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicao_has_usuarios', function (Blueprint $table) {
            $table->dropColumn('desconto_maximo');
        });
    }
}
