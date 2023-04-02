<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProcedimentosConveniosIncCampoValor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos_instituicoes_convenios', function (Blueprint $table) {
            $table->decimal('valor_convenio', 8, 2)->nullable()->default('0.00')->after('valor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos_instituicoes_convenios', function (Blueprint $table) {
            //
        });
    }
}
