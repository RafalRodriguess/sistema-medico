<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableIncCamposContasReceber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->foreignId('conta_pai')->nullable()->default(null)->references('id')->on('contas_receber');
            $table->integer('num_parcelas')->nullable()->default(null);
            $table->decimal('valor_total', 10,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contas_receber', function (Blueprint $table) {
            //
        });
    }
}
