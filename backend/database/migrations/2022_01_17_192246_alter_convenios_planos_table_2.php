<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterConveniosPlanosTable2 extends Migration
{
    private $schema_table = 'convenios_planos';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table($this->schema_table, function (Blueprint $table) {
            $table->boolean('permissao_internacao')->default(false);
            $table->boolean('permissao_emergencia')->default(false);
            $table->boolean('permissao_home_care')->default(false);
            $table->boolean('permissao_ambulatorio')->default(false);
            $table->boolean('permissao_externo')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->schema_table, function (Blueprint $table) {
            $table->dropColumn('permissao_internacao');
            $table->dropColumn('permissao_emergencia');
            $table->dropColumn('permissao_home_care');
            $table->dropColumn('permissao_ambulatorio');
            $table->dropColumn('externo');
        });
    }
}
