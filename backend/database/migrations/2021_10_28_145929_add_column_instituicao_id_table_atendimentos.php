<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInstituicaoIdTableAtendimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->foreignId('instituicao_id')
                ->references('id')
                ->on('instituicoes')
                ->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('atendimentos', function (Blueprint $table) {
            $table->dropColumn('instituicao_id');
        });
    }
}
