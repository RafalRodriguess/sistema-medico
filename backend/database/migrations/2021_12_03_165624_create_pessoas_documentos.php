<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePessoasDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pessoas_documentos', function (Blueprint $table) {
            $table->id();
            $table->integer('tipo');
            $table->string('descricao')->nullable();
            $table->string('file_path_name');
            $table->foreignId('pessoa_id')->references('id')->on('pessoas');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pessoas_documentos');
    }
}
