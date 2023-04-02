<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInstituicoesApiBB extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->integer('apibb_possui')->default(0);
            $table->text('apibb_codigo_cedente')->nullable();
            $table->text('apibb_indicador_pix')->nullable();
            $table->text('apibb_client_id')->nullable();
            $table->text('apibb_client_secret')->nullable();
            $table->text('apibb_gw_dev_app_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->dropColumn('apibb_possui');
            $table->dropColumn('apibb_codigo_cedente');
            $table->dropColumn('apibb_indicador_pix');
            $table->dropColumn('apibb_client_id');
            $table->dropColumn('apibb_client_secret');
            $table->dropColumn('apibb_gw_dev_app_key');
        });
    }
}
