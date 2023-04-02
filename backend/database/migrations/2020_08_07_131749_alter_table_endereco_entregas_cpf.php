<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableEnderecoEntregasCpf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('endereco_entregas', function (Blueprint $table) {
            $table->string('cpf', 15)->nullable()->default(null)->change();
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
            $table->dropColumn('cpf');
        });
    }
}
