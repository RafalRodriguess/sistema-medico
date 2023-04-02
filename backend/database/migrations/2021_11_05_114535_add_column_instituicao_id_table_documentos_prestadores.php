<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInstituicaoIdTableDocumentosPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos_prestadores', function (Blueprint $table) {
            $table->foreignId('instituicao_id')
                ->nullable()
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
        Schema::table('documentos_prestadores', function (Blueprint $table) {
            $table->dropColumn('instituicao_id');
        });
    }
}
