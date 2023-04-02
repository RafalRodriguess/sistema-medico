<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableContatosPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contatos_prestadores', function (Blueprint $table) {
            $table->id();
            $table->string('contato');
            $table->integer('tipo_contato_id');
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
        Schema::dropIfExists('contatos_prestadores');
    }
}
