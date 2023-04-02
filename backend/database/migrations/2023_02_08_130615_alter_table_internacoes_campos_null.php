<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInternacoesCamposNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            $table->foreignId('origem_id')->nullable()->change();
            $table->foreignId('medico_id')->nullable()->change();
            $table->foreignId('acomodacao_id')->nullable()->change();
            $table->foreignId('unidade_id')->nullable()->change();
            $table->foreignId('cid_id')->nullable()->change();
            $table->integer('tipo_internacao')->nullable()->change();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            //
        });
    }
}
