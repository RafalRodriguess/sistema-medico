<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFretesRetirada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fretes_retirada', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fretes_id')->references('id')->on('fretes');
            $table->string('nome','255');
            $table->string('rua', '255');
            $table->string('numero', '25');
            $table->string('bairro', '255');
            $table->string('cidade', '255');
            $table->string('estado', '255');
            $table->string('cep', '255');
            $table->softDeletes();
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
        Schema::dropIfExists('fretes_retirada');
    }
}
