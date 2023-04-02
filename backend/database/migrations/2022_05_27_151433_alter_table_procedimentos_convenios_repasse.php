<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableProcedimentosConveniosRepasse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos_convenios_has_repasse_medico', function (Blueprint $table) {
            $table->decimal('valor_cobrado', 8, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos_convenios_has_repasse_medico', function (Blueprint $table) {
            $table->dropColumn('valor_cobrado');
        });
    }
}
