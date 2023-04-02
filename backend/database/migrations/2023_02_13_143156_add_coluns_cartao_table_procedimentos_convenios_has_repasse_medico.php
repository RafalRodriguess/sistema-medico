<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunsCartaoTableProcedimentosConveniosHasRepasseMedico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos_convenios_has_repasse_medico', function (Blueprint $table) {
            $table->string('tipo_cartao')->comment('dinheiro ou porcentagem')->nullable()->default('dinheiro');
            $table->decimal('valor_repasse_cartao', 8,2)->nullable()->default(0);
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
            $table->dropColumn('tipo_cartao');
            $table->dropColumn('valor_repasse_cartao');
        });
    }
}
