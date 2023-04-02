<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDocumentosPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_prestadores', function (Blueprint $table) {
            $table->id();
            $table->string('file_path_name');
            $table->integer('tipo');
            $table->string('descricao', '255');
            $table->foreignId('prestador_id')->references('id')->on('prestadores');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('documentos_prestadores');
    }
}
