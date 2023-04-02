<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInstituicaoPrestadorIdTableDocumentosPrestador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentos_prestadores', function (Blueprint $table) {
            $table->foreignId('instituicao_prestador_id')
                ->nullable()->references('id')->on('instituicoes_prestadores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentos_prestador', function (Blueprint $table) {
        });
    }
}
